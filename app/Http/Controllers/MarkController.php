<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\ClassTeacherYearModel;
use App\Models\ExamModel;
use App\Models\ExamTypeModel;
use App\Models\MarkModel;
use App\Models\StudentModel;
use App\Models\StudentSummaryModel;
use App\Models\SubjectModel;
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
        $marks = $this->markRepository->getMarks($classId, $examTypeId, $syllabusId, $yearId);
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
        // Analyze attendance and adjust marks
        $marks->transform(function ($mark) {
            if ($mark->status === 'absent') {
                $mark->mark = 'TH'; // Indicate absent with "TH"
            }
            return $mark;
        });
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
            // 'attendance' => 'array',
            'summary' => 'array',
            'summary.*' => 'nullable|string|max:500', // Allow up to 500 characters for summaries
            'status' => 'array',
            'status.*.*' => 'in:present,absent',
        ]);

        // \Log::info('Validated:', $validated);

        // Extract data
        $marks = $request->input('marks', []);
        // $attendance = $request->input('attendance', []);
        $summaries = $request->input('summary', []); // Extract summary data
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
                    // 'attendance' => $attendance[$studentId] ?? null,
                    'total_marks' => $totalMarks,
                    'total_grade' => $totalGrade,
                    'percentage' => round($percentage, 2), // Save percentage with 2 decimal points
                    'summary' => $summaries[$studentId] ?? null, // Update summary instead of attendance
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
        $selectedAcademicYear = AcademicYearModel::findOrFail($yearId);
        // Fetch main data
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $subject = SubjectModel::findOrFail($subjectId);
        $class = ClassModel::findOrFail($classId);
        // Fetch marks for the given class, subject, exam type, and academic year
        $marks = MarkModel::with('student') // Load the related student data
            ->where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $yearId)
            ->where('subject_id', $subjectId)
            ->get();
        // Fetch all students in the class, regardless of marks
        $students = StudentModel::whereHas('classes', fn($query) => $query->where('class_id', $classId))->get();
        // Fetch additional details for the breadcrumb
        $breadcrumbData = [
            'examTypeName' => $examType->exam_type_name,
            'syllabusName' => $syllabus->syllabus_name,
            'className' => $class->name,
            'subjectName' => $subject->subject_name,
        ];
        return view('teacher.examData.marks', compact(
            'marks', 'students', 'yearId', 'selectedAcademicYear', 'examType', 'syllabus', 'subject', 'class', 'breadcrumbData'
        ));
    }

    public function teacherSubjectClassMarkEdit(Request $request)
    {
        \Log::info('Request Parameters:', $request->all());

        // Validate incoming request
        $validatedData = $request->validate([
            'marks.*.student_id' => 'required|exists:student,id',
            'marks.*.mark' => 'required|integer|min:0|max:100',
            'status' => 'array',
            'status.*.*' => 'in:present,absent', // Nested array validation
            'class_id' => 'required|integer',
            'syllabus_id' => 'required|integer',
            'exam_type_id' => 'required|integer',
            'academic_year_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);

        $classId = $validatedData['class_id'];
        $syllabusId = $validatedData['syllabus_id'];
        $examTypeId = $validatedData['exam_type_id'];
        $academicYearId = $validatedData['academic_year_id'];
        $subjectId = $validatedData['subject_id'];
        $marks = $validatedData['marks'];
        $statuses = $request->input('status', []);

        foreach ($marks as $markData) {
            $studentId = $markData['student_id'];
            $status = $statuses[$studentId][$subjectId] ?? 'present'; // Retrieve status by student and subject

            \Log::info("Processing student ID: {$studentId}, Subject ID: {$subjectId}", [
                'status' => $status,
                'mark' => $markData['mark'],
            ]);

            $finalMark = ($status === 'absent') ? 0 : $markData['mark'];

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
        }

        return redirect()->route('teacher.exams.marks', [
            'yearId' => $academicYearId,
            'examTypeId' => $examTypeId,
            'syllabusId' => $syllabusId,
            'subjectId' => $subjectId,
            'classId' => $classId,
        ])->with('success', 'Marks updated successfully!');
    }

    public function classExamReportClassTeacher($yearId = null, $examTypeId, $syllabusId, $examId)
    {
        $teacherId = auth()->id();
        $yearId = session('academic_year_id'); // Use session year ID if no parameter provided
        $selectedAcademicYear = AcademicYearModel::find($yearId);

        if (!$selectedAcademicYear) {
            return redirect()->back()->with('error', 'No academic year is selected or available.');
        }

        $syllabus = SyllabusModel::find($syllabusId);
        $examType = ExamTypeModel::select('id', 'exam_type_name')->find($examTypeId);
        $exams = ExamModel::where('academic_year_id', $yearId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->get()
            ->keyBy('syllabus_id'); // Key exams by syllabus_id for easy lookup

        $exams2 = ExamModel::where('academic_year_id', $yearId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->first(); // Retrieve the first matching exam

        if (!$syllabus || !$examType || !$exams) {
            return redirect()->back()->with('error', 'Required data (syllabus, exam type, or exam) is not available.');
        }

        $classTeacherYear = ClassTeacherYearModel::with(['class.students'])
            ->where('teacher_id', $teacherId)
            ->where('academic_year_id', $yearId)
            ->first();

        if (!$classTeacherYear || !$classTeacherYear->class) {
            return redirect()->route('teacher.classTeacher.examTypeList', ['yearId' => $yearId])
                ->with('error', 'No class assigned to this teacher for the selected academic year.');
        }
        $class = $classTeacherYear->class;
        // Fetch students in the class
        $students = StudentModel::whereHas('classes', function ($query) use ($class) {
            $query->where('class_id', $class->id);
        })->get();
        if ($students->isEmpty()) {
            return redirect()->route('teacher.classTeacher.examTypeList', ['yearId' => $yearId])
                ->with('error', 'No students found for the assigned class.');
        }

        // Fetch marks for the students in the class
        $marks = MarkModel::where('class_id', $class->id)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $yearId)
            ->get()
            ->groupBy('student_id');

        // Fetch summary for students in the class and the specific exam
        $studentsSummary = StudentSummaryModel::where('exam_id', $examId)
            ->where('class_id', $class->id)
            ->get();

        // Fetch subjects associated with the class and syllabus
        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('academic_year_id', $yearId)
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();

        return view('teacher.classTeacher.classExamReport', compact(
            'class',
            'selectedAcademicYear',
            'examType',
            'exams',
            'exams2',
            'syllabus',
            'marks',
            'studentsSummary',
            'students',
            'subjects'
        ));
    }

    // public function writeSummaryClassTeacher($yearId, $examTypeId, $syllabusId, $examId, $classId, $studentId)
    // {
    //     // Fetch academic year and check validity
    //     $selectedAcademicYear = AcademicYearModel::find($yearId);
    //     if (!$selectedAcademicYear) {
    //         return redirect()->back()->with('error', 'Invalid academic year.');
    //     }

    //     // Fetch student
    //     $student = StudentModel::findOrFail($studentId);

    //     // Fetch marks for the student
    //     $marks = MarkModel::where('student_id', $studentId)
    //         ->where('class_id', $classId)
    //         ->where('exam_type_id', $examTypeId)
    //         ->where('syllabus_id', $syllabusId)
    //         ->where('academic_year_id', $yearId)
    //         ->get();

    //     // Fetch summary for the student
    //     $studentSummary = StudentSummaryModel::where('exam_id', $examId)
    //         ->where('class_id', $classId)
    //         ->where('student_id', $studentId)
    //         ->first();

    //     return view('teacher.classTeacher.writeSummary', compact(
    //         'selectedAcademicYear',
    //         'examTypeId',
    //         'syllabusId',
    //         'examId',
    //         'classId',
    //         'student',
    //         'marks',
    //         'studentSummary'
    //     ));
    // }

    public function writeSummaryClassTeacher($yearId, $examTypeId, $syllabusId, $examId, $classId, $studentId)
    {
        // Fetch academic year and check validity
        $selectedAcademicYear = AcademicYearModel::find($yearId);
        if (!$selectedAcademicYear) {
            return redirect()->back()->with('error', 'Invalid academic year.');
        }

        // Fetch exam type
        $examType = ExamTypeModel::select('id', 'exam_type_name')->findOrFail($examTypeId);

        // Fetch syllabus
        $syllabus = SyllabusModel::findOrFail($syllabusId);

        // Fetch student
        $student = StudentModel::findOrFail($studentId);

        // Fetch marks for the student
        $marks = MarkModel::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $yearId)
            ->get();

        // Fetch summary for the student
        $studentSummary = StudentSummaryModel::where('exam_id', $examId)
            ->where('class_id', $classId)
            ->where('student_id', $studentId)
            ->first();

        return view('teacher.classTeacher.writeSummary', compact(
            'selectedAcademicYear',
            'examType',
            'syllabus',
            'examTypeId',
            'syllabusId',
            'examId',
            'classId',
            'student',
            'marks',
            'studentSummary'
        ));
    }

    public function writeSummaryClassTeacherPost(Request $request, $yearId, $examTypeId, $syllabusId, $examId, $classId, $studentId)
    {
        $request->validate([
            'summary' => 'nullable|string|max:500',
        ]);

        \Log::info($request->all());

        try {
            // Update or create student summary
            StudentSummaryModel::updateOrCreate(
                [
                    'exam_id' => $examId,
                    'class_id' => $classId,
                    'student_id' => $studentId,
                ],
                [
                    'summary' => $request->summary,
                ]
            );

            return redirect()->route('teacher.classTeacher.classExamReport', [$yearId, $examTypeId, $syllabusId, $examId])
                ->with('success', 'Summary saved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while saving the summary.');
        }
    }

    // //Report
    // public function positionInClassReport($yearId, $examTypeId, $syllabusId, $classId, $studentId)
    // {
    //     // Use the repository to fetch the student summary
    //     $studentSummary = $this->summaryRepository->getStudentSummary($studentId, $classId, $examTypeId, $syllabusId, $yearId);

    //     // Check if summary exists and fetch positions
    //     if ($studentSummary) {
    //         // Assuming the position calculation is done periodically or before this request
    //         $positionInClass = $studentSummary->position_in_class;
    //     } else {
    //         // Handle cases where the summary is not available
    //         $positionInClass = 'Not available';
    //     }

    //     // Prepare data for the PDF
    //     $data = [
    //         'student' => $studentSummary->student,
    //         'positionInClass' => $positionInClass,
    //     ];

    //     // Generate and return the PDF
    //     $pdf = Pdf::loadView('your.view.path', $data);
    //     return $pdf->stream('position_in_class_report.pdf');
    // }

    // public function positionInYearLevelReport($yearId, $examTypeId, $syllabusId, $classId, $studentId)
    // {
    //     // Load necessary models
    //     $student = StudentModel::findOrFail($studentId);
    //     $year = AcademicYearModel::findOrFail($yearId);

    //     // Assume some method to calculate rank or fetch it if already calculated
    //     $positionInYearLevel = $this->summaryRepository->getStudentSummary($studentId, $classId, $examTypeId, $syllabusId, $yearId);

    //     // Prepare data for the PDF
    //     $data = [
    //         'student' => $student,
    //         'year' => $year,
    //         'positionInYearLevel' => $positionInYearLevel,
    //     ];

    //     // Load the PDF view and pass the data
    //     $pdf = Pdf::loadView('admin.examManagement.exams.marks.positionInYearLevelReport', $data);

    //     // Return PDF stream
    //     return $pdf->stream('Position_in_Year_Level_Report_' . $student->full_name . '.pdf');
    // }

    // public function positionInClassReport($yearId, $examTypeId, $syllabusId, $classId, $studentId)
    // {
    //     try {
    //         $studentSummary = $this->summaryRepository->getStudentSummary($studentId, $classId, $examTypeId, $syllabusId, $yearId);

    //         if (!$studentSummary) {
    //             abort(404, 'Student summary not available.');
    //         }

    //         $data = [
    //             'student' => $studentSummary->student,
    //             'positionInClass' => $studentSummary->position_in_class ?? 'Not available',
    //         ];

    //         $pdf = PDF::loadView('admin.examManagement.exams.marks.class-position', $data);
    //         return $pdf->stream('position_in_class_report_' . $studentSummary->student->full_name . '.pdf');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withErrors('Error generating report: ' . $e->getMessage());
    //     }
    // }

    // public function positionInYearLevelReport($yearId, $examTypeId, $syllabusId, $classId, $studentId)
    // {
    //     try {
    //         $student = StudentModel::findOrFail($studentId);
    //         $year = AcademicYearModel::findOrFail($yearId);

    //         $studentSummary = $this->summaryRepository->getStudentSummary($studentId, $classId, $examTypeId, $syllabusId, $yearId);

    //         if (!$studentSummary) {
    //             abort(404, 'Student summary not available.');
    //         }

    //         $data = [
    //             'student' => $student,
    //             'year' => $year,
    //             'positionInYearLevel' => $studentSummary->position_in_year_level ?? 'Not available',
    //         ];

    //         $pdf = PDF::loadView('admin.examManagement.exams.marks.year-level-position', $data);
    //         return $pdf->stream('Position_in_Year_Level_Report_' . $student->full_name . '.pdf');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withErrors('Error generating report: ' . $e->getMessage());
    //     }
    // }

    // public function positionInClassReport($yearId, $examTypeId, $syllabusId, $classId, $examId, $studentId)
    // {
    //     // try {
    //     $studentSummary = $this->summaryRepository->getStudentSummary($studentId, $classId, $examTypeId, $syllabusId, $yearId, $examId);

    //     if (!$studentSummary) {
    //         abort(404, 'Student summary not available.');
    //     }

    //     $data = [
    //         'student' => $studentSummary->student,
    //         'positionInClass' => $studentSummary->position_in_class ?? 'Not available',
    //     ];

    //     $pdf = PDF::loadView('admin.examManagement.exams.marks.class-position', $data);
    //     return $pdf->stream('position_in_class_report_' . $studentSummary->student->full_name . '.pdf');
    //     // } catch (\Exception $e) {
    //     //     return redirect()->back()->withErrors('Error generating report: ' . $e->getMessage());
    //     // }
    // }

    public function positionInClassReport($yearId, $examTypeId, $syllabusId, $classId, $examId, $studentId)
    {
        try {
            $student = StudentModel::findOrFail($studentId);
            $year = AcademicYearModel::findOrFail($yearId);
            $class = ClassModel::findOrFail($classId);  // Ensure $class is fetched here

            // Fetch all summaries for the class based on the exam ID
            $studentSummaries = $this->summaryRepository->getSummariesAscending($examId, $classId);

            if (!$studentSummaries) {
                abort(404, 'Student summaries not available.');
            }

            // Prepare the data for the view
            $data = [
                'class' => $class,  // Make sure to include this
                'year' => $year,
                'studentSummaries' => $studentSummaries  // Changed from 'studentSummary' to 'studentSummaries'
            ];

            // Load the view with the appropriate data
            $pdf = PDF::loadView('admin.examManagement.exams.marks.class-position', $data);
            return $pdf->stream('position_in_class_report_' . $student->full_name . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error generating report: ' . $e->getMessage());
        }
    }

    public function positionInYearLevelReport($yearId, $examTypeId, $syllabusId, $classId, $examId, $studentId)
    {
        try {
            $student = StudentModel::findOrFail($studentId);
            $year = AcademicYearModel::findOrFail($yearId);
            $class = ClassModel::findOrFail($classId);  // Ensure $class is fetched here

            // Fetch all summaries for the class based on the exam ID
            $studentSummaries = $this->summaryRepository->getSummariesAscending($examId, $classId);

            if (!$studentSummaries) {
                abort(404, 'Student summaries not available.');
            }

            // Prepare the data for the view
            $data = [
                'class' => $class,  // Make sure to include this
                'year' => $year,
                'studentSummaries' => $studentSummaries  // Changed from 'studentSummary' to 'studentSummaries'
            ];

            // Load the view with the appropriate data
            $pdf = PDF::loadView('admin.examManagement.exams.marks.year-level-position', $data);
            return $pdf->stream('Position_in_Year_Level_Report_' . $student->full_name . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error generating report: ' . $e->getMessage());
        }
    }


}
