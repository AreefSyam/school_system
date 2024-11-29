<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
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

    public function index($yearId, $examTypeId, $syllabusId, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $year = AcademicYearModel::findOrFail($yearId);

        $marks = $this->markRepository->getMarks($classId, $examTypeId, $syllabusId, $yearId);
        $students = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('academic_year_id', $yearId) // Filter by year
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();

        $studentsSummary = $this->summaryRepository->getSummaries($classId, $examTypeId, $syllabusId, $yearId);

        return view('admin.examManagement.exams.marks.tableMarkClass', compact('class', 'syllabus', 'examType', 'year', 'students', 'marks', 'subjects', 'studentsSummary'));
    }

    public function edit($yearId, $examTypeId, $syllabusId, $classId)
    {
        // Fetch the related data
        $class = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $year = AcademicYearModel::findOrFail($yearId);

        // Get marks from the repository
        $marks = $this->markRepository->getMarks($classId, $examTypeId, $syllabusId, $yearId);

        // Fetch students in the class
        $students = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        // Fetch subjects for the syllabus and grade level
        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();

        // Get student summaries for attendance and other details
        $studentsSummary = $this->summaryRepository->getSummaries($classId, $examTypeId, $syllabusId, $yearId);

        // Return the editable view
        return view('admin.examManagement.exams.marks.tableMarkClassEditable', compact(
            'class',
            'syllabus',
            'examType',
            'year',
            'students',
            'marks',
            'subjects',
            'studentsSummary'
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
        // Validate request input
        $request->validate([
            'class_id' => 'required|integer',
            'syllabus_id' => 'required|integer',
            'exam_type_id' => 'required|integer',
            'academic_year_id' => 'required|integer',
            'marks' => 'required|array',
            'marks.*' => 'array',
            'marks.*.*' => 'integer|min:0|max:100', // Assuming marks range from 0 to 100
            'attendance' => 'array',
        ]);

        // Extract data
        $marks = $request->input('marks', []);
        $attendance = $request->input('attendance', []);
        $classId = $request->class_id;
        $examTypeId = $request->exam_type_id;
        $syllabusId = $request->syllabus_id;
        $academicYearId = $request->academic_year_id;

        // Fetch the grade level ID of the class for position calculations
        $gradeLevelId = ClassModel::findOrFail($classId)->grade_level_id;

        // Define grade thresholds
        $gradeThresholds = [
            'A' => 80,
            'B' => 60,
            'C' => 40,
            'D' => 0,
        ];

        // Process each student's marks
        foreach ($marks as $studentId => $subjects) {
            $numSubjects = count($subjects);
            $maxMarks = $numSubjects * 100; // Maximum marks = 100 * number of subjects

            // Update student marks and calculate total marks
            $totalMarks = $this->updateStudentMarks($subjects, $studentId, $classId, $syllabusId, $examTypeId, $academicYearId);

            // Calculate percentage
            $percentage = $numSubjects > 0 ? ($totalMarks / $maxMarks) * 100 : 0;

            // Calculate the total grade
            $totalGrade = $this->calculateTotalGrade($subjects, $gradeThresholds);

            // Update attendance, total marks, and total grade
            StudentSummaryModel::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'exam_type_id' => $examTypeId,
                    'syllabus_id' => $syllabusId,
                    'academic_year_id' => $academicYearId,
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

        return redirect()->route('exams.marks', [
            'yearId' => $academicYearId,
            'examTypeId' => $examTypeId,
            'syllabusId' => $syllabusId,
            'classId' => $classId,
        ])->with('success', 'Marks, percentages, and summaries updated successfully.');
    }

    // Update or create marks for a student and calculate total marks.
    private function updateStudentMarks(array $subjects, int $studentId, int $classId, int $syllabusId, int $examTypeId, int $academicYearId): int
    {
        $totalMarks = 0;

        foreach ($subjects as $subjectId => $mark) {
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
                    'mark' => $mark,
                ]
            );

            $totalMarks += $mark;
        }

        return $totalMarks;
    }

    // Calculate the total grade string for a student based on their marks.
    private function calculateTotalGrade(array $subjects, array $gradeThresholds): string
    {
        $grades = array_fill_keys(array_keys($gradeThresholds), 0);

        foreach ($subjects as $mark) {
            foreach ($gradeThresholds as $grade => $threshold) {
                if ($mark >= $threshold) {
                    $grades[$grade]++;
                    break;
                }
            }
        }

        return collect($grades)
            ->map(fn($count, $grade) => "{$count}{$grade}")
            ->implode(' ');
    }
}
