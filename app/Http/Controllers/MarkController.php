<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\ExamTypeModel;
use App\Models\MarkModel;
use App\Models\StudentModel;
use App\Models\StudentSummaryModel;
use App\Models\SyllabusModel;
use App\Repositories\MarkRepository;
use App\Repositories\StudentSummaryRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{
    protected $markRepository;
    protected $summaryRepository;

    public function __construct(MarkRepository $markRepository, StudentSummaryRepository $summaryRepository)
    {
        $this->markRepository = $markRepository;
        $this->summaryRepository = $summaryRepository;
    }

    public function index($yearId, $examTypeId, $syllabusId, $classId, $examId)
    {
        $class = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $exam = ExamModel::findOrFail($examId);
        $year = AcademicYearModel::findOrFail($yearId);

        $marks = $this->markRepository->getMarks($classId, $examTypeId, $syllabusId, $yearId);
        $studentsSummary = $this->summaryRepository->getSummaries($examId, $classId);
        $students = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('academic_year_id', $yearId)
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();

        return view('admin.examManagement.exams.marks.tableMarkClass', compact('class', 'syllabus', 'examType', 'exam', 'year', 'students', 'marks', 'subjects', 'studentsSummary'));
    }

    public function edit($yearId, $examTypeId, $syllabusId, $classId, $examId)
    {
        // Fetch the related data
        $class = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $exam = ExamModel::findOrFail($examId);
        $year = AcademicYearModel::findOrFail($yearId);

        // Get marks from the repository using the examId
        $marks = $this->markRepository->getMarks($classId, $examTypeId, $syllabusId, $yearId, $examId);
        // Fetch students in the class
        $students = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();
        // Fetch subjects for the syllabus and grade level
        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('academic_year_id', $yearId)
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();
        // Get student summaries for attendance and other details using examId
        $studentsSummary = $this->summaryRepository->getSummaries($examId, $classId);
        // Return the editable view
        return view('admin.examManagement.exams.marks.tableMarkClassEditable', compact(
            'class',
            'syllabus',
            'examType',
            'year',
            'students',
            'marks',
            'subjects',
            'studentsSummary',
            'exam'
        ));
    }

    public function generateStudentReport($yearId, $examTypeId, $syllabusId, $classId, $studentId)
    {
        // Fetch the related data using models
        $class = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $year = AcademicYearModel::findOrFail($yearId);
        $student = StudentModel::findOrFail($studentId);
        // Fetch marks for the student
        $marks = $this->markRepository->getStudentMarks($studentId, $classId, $examTypeId, $syllabusId, $yearId);
        // Fetch subjects for the syllabus and grade level
        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();
        // Fetch the student summary
        $studentSummary = $this->summaryRepository->getStudentSummary($studentId, $classId, $examTypeId, $syllabusId, $yearId);
        // Prepare data for the PDF
        $data = [
            'student' => $student,
            'class' => $class,
            'syllabus' => $syllabus,
            'examType' => $examType,
            'year' => $year,
            'marks' => $marks->groupBy('subject_id'),
            'subjects' => $subjects,
            'studentSummary' => $studentSummary,
        ];
        // Load the PDF view and pass the data
        $pdf = Pdf::loadView('admin.examManagement.exams.marks.studentReport', $data);
        // Stream the PDF
        return $pdf->stream('Student_Report_' . $student->full_name . '.pdf');
    }

    public function updateAll(Request $request)
    {
        // \Log::info('Marks:', $request->input('marks'));
        // \Log::info('Status:', $request->input('status'));

        // Validate request input
        $request->validate([
            'class_id' => 'required|integer',
            'syllabus_id' => 'required|integer',
            'exam_type_id' => 'required|integer',
            'academic_year_id' => 'required|integer',
            'marks' => 'required|array',
            'marks.*' => 'array',
            'marks.*.*' => 'integer|min:0|max:100',
            'attendance' => 'array',
            'status' => 'array',
            'status.*.*' => 'in:present,absent',
        ]);

        // \Log::info('Validated:', $validated);

        // Extract data
        $marks = $request->input('marks', []);
        $attendance = $request->input('attendance', []);
        // new
        $status = $request->input('status', []);
        // $status = $validated['status'];
        $classId = $request->class_id;
        $examTypeId = $request->exam_type_id;
        $examId = $request->examId; // Added this to handle exam_id
        $syllabusId = $request->syllabus_id;
        $academicYearId = $request->academic_year_id;
        $gradeLevelId = ClassModel::findOrFail($classId)->grade_level_id; // Fetch the grade level ID of the class for position calculations

        // \Log::info('Status EHE:', $status);

        // Define grade thresholds
        $gradeThresholds = [
            'A' => 80,
            'B' => 60,
            'C' => 40,
            'D' => 0,
            'TH' => 0,
        ];
        // Process each student's marks
        foreach ($marks as $studentId => $subjects) {

            $numSubjects = count($subjects);
            $maxMarks = $numSubjects * 100; // Maximum marks = 100 * number of subjects
            // Update student marks and calculate total marks
            $totalMarks = $this->updateStudentMarks($subjects, $studentId, $classId, $syllabusId, $examTypeId, $academicYearId, $status);
            // Calculate percentage
            $percentage = $numSubjects > 0 ? ($totalMarks / $maxMarks) * 100 : 0;
            // Calculate the total grade

            $totalGrade = $this->calculateTotalGrade($subjects, $studentId, $gradeThresholds, $academicYearId, $syllabusId, $classId, $status);

            // Update attendance, total marks, and total grade
            StudentSummaryModel::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'exam_type_id' => $examTypeId,
                    'syllabus_id' => $syllabusId,
                    'academic_year_id' => $academicYearId,
                    'exam_id' => $examId,
                ],
                [
                    'attendance' => $attendance[$studentId] ?? null,
                    'total_marks' => $totalMarks,
                    'total_grade' => $totalGrade,
                    'percentage' => round($percentage, 2), // Save percentage with 2 decimal points
                ]
            );
        }
        // Calculate positions after updates
        $this->summaryRepository->calculatePositions($classId, $examTypeId, $syllabusId, $academicYearId, $gradeLevelId);

        if (!isset($examId)) {
            return back()->withErrors('Exam ID is required.');
        }

        return redirect()->route('exams.marks', [
            'yearId' => $academicYearId,
            'examTypeId' => $examTypeId,
            'syllabusId' => $syllabusId,
            'classId' => $classId,
            'examId' => $examId,
        ])->with('success', 'Marks, percentages, and summaries updated successfully.');
    }

    private function updateStudentMarks($subjects, $studentId, $classId, $syllabusId, $examTypeId, $academicYearId, $statuses)
    {

        $totalMarks = 0;
        foreach ($subjects as $subjectId => $mark) {
            $status = $statuses[$studentId][$subjectId];
            $finalMark = ($status === 'absent') ? 0 : $mark;
            // \Log::info("Updating mark for student {$studentId}, subject {$subjectId}: mark={$finalMark}, status={$status}");

            MarkModel::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'class_id' => $classId,
                    'syllabus_id' => $syllabusId,
                    'exam_type_id' => $examTypeId,
                    'academic_year_id' => $academicYearId,
                ],
                [
                    'mark' => $finalMark,
                    'status' => $status,
                ]
            );
            // if ($markRecord->wasRecentlyCreated || $markRecord->wasChanged()) {
            //     \Log::info("Mark successfully updated for student {$studentId}, subject {$subjectId}");
            // } else {
            //     \Log::error("Failed to update mark for student {$studentId}, subject {$subjectId}");
            // }
            if ($status !== 'absent') {
                $totalMarks += $finalMark;
            }
        }
        return $totalMarks;
    }

    // Calculate the total grade string for a student based on their marks.
    private function calculateTotalGrade(array $subjects, int $studentId, array $gradeThresholds, $yearId, $syllabusId, $classId, $statuses)
    {
        // Initialize grades including 'TH' for Tidak Hadir (absent)
        $grades = array_fill_keys(array_keys($gradeThresholds), 0);

        // Fetch statuses directly from the database
        // $statuses = DB::table('marks')
        //     ->where('student_id', $studentId)
        //     ->where('class_id', $classId)
        //     ->where('academic_year_id', $yearId)
        //     ->where('syllabus_id', $syllabusId)
        //     ->pluck('status', 'subject_id');

        foreach ($subjects as $subjectId => $mark) {
            // $status = $statuses[$subjectId];
            $status = $statuses[$studentId][$subjectId] ?? 'absent'; // Default to 'absent' if status is missing
            if ($status === 'absent') {
                if ($mark == 0) {
                    $grades['TH']++;
                    continue;
                }
            } else if ($status === 'present') {
                foreach ($gradeThresholds as $grade => $threshold) {
                    if ($mark >= $threshold) {
                        $grades[$grade]++;
                        break;
                    }
                }
            }
        }
        // Prepare the formatted output for each grade, including 'TH'
        // return collect($grades)
        //     ->map(function ($count, $grade) {
        //         if ($count != 0) {
        //             return "{$count}{$grade}";
        //         }

        //     })
        //     ->implode(' ');

        return collect($grades)
            ->filter(function ($count) {
                return $count > 0; // Only include grades with a count greater than 0
            })
            ->map(function ($count, $grade) {
                return "{$count}{$grade}"; // Format the remaining grades
            })
            ->implode(' '); // Combine into a single string
    }

    //TEACHER
    public function teacherSubjectClassMark($yearId = null, $examTypeId, $syllabusId, $subjectId, $classId)
    {
        $teacherId = auth()->id();

        $yearId = session('academic_year_id');

        // Fetch marks for the given class, subject, exam type, and academic year
        $marks = MarkModel::with('student') // Load the related student data
            ->where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $yearId)
            ->where('subject_id', $subjectId)
            ->get();

        // Fetch all students in the class, regardless of marks
        $students = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        // $students = StudentModel::whereHas('classes', function ($query) use ($classId, $yearId) {
        //     $query->where('class_id', $classId)
        //         ->where('academic_year_id', $yearId);
        // })->get();

        // Fetch additional details for the breadcrumb
        $selectedAcademicYear = AcademicYearModel::findOrFail($yearId);
        $examTypeName = DB::table('exam_type')->where('id', $examTypeId)->value('exam_type_name');
        $syllabusName = DB::table('syllabus')->where('id', $syllabusId)->value('syllabus_name');
        $className = DB::table('class')->where('id', $classId)->value('name');
        $subjectName = DB::table('subject')->where('id', $subjectId)->value('subject_name');

        return view('teacher.examData.marks', compact(
            'marks',
            'students',
            'subjectName',
            'yearId',
            'examTypeId',
            'syllabusId',
            'subjectId',
            'classId',
            'selectedAcademicYear',
            'examTypeName',
            'syllabusName',
            'className'
        ));
    }

    // Save marks
    public function teacherSubjectClassMarkEdit(Request $request, $yearId, $examTypeId, $syllabusId, $classId)
    {
        // Validation
        $request->validate([
            'marks.*.student_id' => 'required|exists:students,id',
            'marks.*.subject_id' => 'required|exists:subjects,id',
            'marks.*.score' => 'required|numeric|min:0|max:100',
        ]);

        foreach ($request->marks as $markData) {
            MarkModel::updateOrCreate(
                [
                    'student_id' => $markData['student_id'],
                    'subject_id' => $markData['subject_id'],
                    'class_id' => $classId,
                    'exam_type_id' => $examTypeId,
                    'syllabus_id' => $syllabusId,
                    'academic_year_id' => $yearId,
                ],
                ['score' => $markData['score']]
            );
        }

        return redirect()->back()->with('success', 'Marks updated successfully!');
    }

}
