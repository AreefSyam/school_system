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

    /**
     * Initializes controller with repositories.
     */
    public function __construct(MarkRepository $markRepository, StudentSummaryRepository $summaryRepository)
    {
        $this->markRepository    = $markRepository;
        $this->summaryRepository = $summaryRepository;
    }

    /**
     * Displays the marks table for a specific class and exam configuration.
     */
    public function index($yearId, $examTypeId, $syllabusId, $classId, $examId)
    {
        $class    = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $exam     = ExamModel::findOrFail($examId);
        $year     = AcademicYearModel::findOrFail($yearId);

        $marks           = $this->markRepository->getMarks($classId, $examTypeId, $syllabusId, $yearId);
        $studentsSummary = $this->summaryRepository->getSummaries($examId, $classId);
        $students        = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('subject_grade.active', 1) // Fetch only active records
            ->where('academic_year_id', $yearId)
            ->where('subject.syllabus_id', $syllabusId)
            ->where('subject_grade.grade_level_id', $class->grade_level_id)
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();

        return view('admin.examManagement.exams.marks.tableMarkClass', compact('class', 'syllabus', 'examType', 'exam', 'year', 'students', 'marks', 'subjects', 'studentsSummary'));
    }

    /**
     * Provides an editable marks table for a specific exam setup.
     */
    public function edit($yearId, $examTypeId, $syllabusId, $classId, $examId)
    {
        // Fetch the related data
        $class    = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $exam     = ExamModel::findOrFail($examId);
        $year     = AcademicYearModel::findOrFail($yearId);

        // Get marks from the repository using the examId
        $marks = $this->markRepository->getMarks($classId, $examTypeId, $syllabusId, $yearId);
        // Fetch students in the class
        $students = StudentModel::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();
        // Fetch subjects for the syllabus and grade level
        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('subject_grade.active', 1) // Fetch only active records
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

    /**
     * Generates a detailed report for a student regarding their performance in an exam.
     */
    public function generateStudentReport($yearId, $examTypeId, $syllabusId, $classId, $studentId)
    {
        // Fetch the related data using models
        $class    = ClassModel::findOrFail($classId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $year     = AcademicYearModel::findOrFail($yearId);
        $student  = StudentModel::findOrFail($studentId);
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
            ->where('subject.academic_year_id', $yearId) // Filter by academic year
            ->select('subject.id as subject_id', 'subject.subject_name')
            ->get();
        // Fetch the student summary
        $studentSummary = $this->summaryRepository->getStudentSummary($studentId, $classId, $examTypeId, $syllabusId, $yearId);
        // Prepare data for the PDF
        $data = [
            'student'        => $student,
            'class'          => $class,
            'syllabus'       => $syllabus,
            'examType'       => $examType,
            'year'           => $year,
            'marks'          => $marks->groupBy('subject_id'),
            'subjects'       => $subjects,
            'studentSummary' => $studentSummary,
        ];
        // Load the PDF view and pass the data
        $pdf = Pdf::loadView('admin.examManagement.exams.marks.studentReport', $data);
        // Stream the PDF
        return $pdf->stream('Student_Report_' . $student->full_name . '.pdf');
    }

    /**
     * Updates student marks, grades, summaries, and attendance for a specific exam.
     *
     * This method handles:
     * - Validation of the request data to ensure all required fields are present and valid.
     * - Processing of marks, attendance, and summaries for each student.
     * - Calculation of total marks, percentages, and grades.
     * - Storing the data in the database, including updates to attendance, marks, and summaries.
     * - Calculation of student positions within the class after the updates.
     *
     * @param Request $request The incoming HTTP request containing the data to update.
     * @return \Illuminate\Http\RedirectResponse Redirects to the marks view page with a success message on completion.
     */
    public function updateAll(Request $request)
    {

        // Validate request input
        $request->validate([
            'class_id'         => 'required|integer',
            'syllabus_id'      => 'required|integer',
            'exam_type_id'     => 'required|integer',
            'academic_year_id' => 'required|integer',
            'marks'            => 'required|array',
            'marks.*'          => 'array',
            'marks.*.*'        => 'integer|min:0|max:100',
            'summary'          => 'array',
            'summary.*'        => 'nullable|string|max:500', // Allow up to 500 characters for summaries
            'status'           => 'array',
            'status.*.*'       => 'in:present,absent',
        ]);

        // Extract data
        $marks          = $request->input('marks', []);
        $summaries      = $request->input('summary', []); // Extract summary data
        $status         = $request->input('status', []);
        $classId        = $request->class_id;
        $examTypeId     = $request->exam_type_id;
        $examId         = $request->examId; // Added this to handle exam_id
        $syllabusId     = $request->syllabus_id;
        $academicYearId = $request->academic_year_id;
        $gradeLevelId   = ClassModel::findOrFail($classId)->grade_level_id; // Fetch the grade level ID of the class for position calculations

        // Define grade thresholds
        $gradeThresholds = [
            'A'  => 80,
            'B'  => 60,
            'C'  => 40,
            'D'  => 0,
            'TH' => 0,
        ];
        // Process each student's marks
        foreach ($marks as $studentId => $subjects) {

            $numSubjects = count($subjects);
            // Maximum marks = 100 * number of subjects
            $maxMarks = $numSubjects * 100;
            // Update student marks and calculate total marks
            $totalMarks = $this->updateStudentMarks($subjects, $studentId, $classId, $syllabusId, $examTypeId, $academicYearId, $status);
            // Calculate percentage
            $percentage = $numSubjects > 0 ? ($totalMarks / $maxMarks) * 100 : 0;
            // Calculate the total grade

            $totalGrade = $this->calculateTotalGrade($subjects, $studentId, $gradeThresholds, $academicYearId, $syllabusId, $classId, $status);

            // Update all data in database
            StudentSummaryModel::updateOrCreate(
                [
                    'student_id'       => $studentId,
                    'class_id'         => $classId,
                    'exam_type_id'     => $examTypeId,
                    'syllabus_id'      => $syllabusId,
                    'academic_year_id' => $academicYearId,
                    'exam_id'          => $examId,
                ],
                [
                    'total_marks' => $totalMarks,
                    'total_grade' => $totalGrade,
                    'percentage'  => round($percentage, 2),          // Save percentage with 2 decimal points
                    'summary'     => $summaries[$studentId] ?? null, // Update summary instead of attendance
                ]
            );
        }
        // Calculate positions after updates
        $this->summaryRepository->calculatePositions($classId, $examTypeId, $syllabusId, $academicYearId, $gradeLevelId);

        if (! isset($examId)) {
            return back()->withErrors('Exam ID is required.');
        }

        return redirect()->route('exams.marks', [
            'yearId'     => $academicYearId,
            'examTypeId' => $examTypeId,
            'syllabusId' => $syllabusId,
            'classId'    => $classId,
            'examId'     => $examId,
        ])->with('success', 'Marks, percentages, and summaries updated successfully.');
    }

    private function updateStudentMarks($subjects, $studentId, $classId, $syllabusId, $examTypeId, $academicYearId, $statuses)
    {

        $totalMarks = 0;
        foreach ($subjects as $subjectId => $mark) {
            $status    = $statuses[$studentId][$subjectId];
            $finalMark = ($status === 'absent') ? 0 : $mark;
            // \Log::info("Updating mark for student {$studentId}, subject {$subjectId}: mark={$finalMark}, status={$status}");

            MarkModel::updateOrCreate(
                [
                    'student_id'       => $studentId,
                    'subject_id'       => $subjectId,
                    'class_id'         => $classId,
                    'syllabus_id'      => $syllabusId,
                    'exam_type_id'     => $examTypeId,
                    'academic_year_id' => $academicYearId,
                ],
                [
                    'mark'   => $finalMark,
                    'status' => $status,
                ]
            );
            if ($status !== 'absent') {
                $totalMarks += $finalMark;
            }
        }
        return $totalMarks;
    }

    /**
     * Calculate the total grade string for a student based on their marks.
     * Updates the marks for a specific student across all subjects.
     *
     * Processes the attendance status for each subject, ensuring marks are set to 0 for absent students.
     * Updates or creates the record in the database for each subject and calculates the total marks.
     *
     * @param array $subjects       An array of subjects and their respective marks for the student.
     * @param int $studentId        The ID of the student whose marks are being updated.
     * @param int $classId          The ID of the class the student belongs to.
     * @param int $syllabusId       The ID of the syllabus being used.
     * @param int $examTypeId       The ID of the exam type.
     * @param int $academicYearId   The ID of the academic year.
     * @param array $statuses       An array of attendance statuses for the student across subjects.
     *
     * @return int                  The total marks for the student across all subjects.
     */
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

    /**
     * View For User Teacher
     * Displays marks for a specific subject, class, and exam type for a teacher.
     *
     * Fetches and validates the relevant academic year, exam type, syllabus, subject, and class details.
     * Retrieves all students in the class, their marks for the subject and exam type, and available examinations.
     * Passes the data to a view for display.
     *
     * @param int|null $yearId       The ID of the academic year (if null, retrieves from the session).
     * @param int $examTypeId        The ID of the exam type.
     * @param int $syllabusId        The ID of the syllabus.
     * @param int $subjectId         The ID of the subject.
     * @param int $classId           The ID of the class.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function teacherSubjectClassMark($yearId = null, $examTypeId, $syllabusId, $subjectId, $classId)
    {
        $teacherId = auth()->id();
        $yearId    = session('academic_year_id');

        if (! $yearId) {
            abort(404, 'No academic year is currently active.');
        }

        $selectedAcademicYear = AcademicYearModel::findOrFail($yearId);

        // Fetch main data
        $examType = ExamTypeModel::findOrFail($examTypeId);
        $syllabus = SyllabusModel::findOrFail($syllabusId);
        $subject  = SubjectModel::findOrFail($subjectId);
        $class    = ClassModel::findOrFail($classId);

        // Fetch all students in the class, regardless of marks
        $students = StudentModel::whereHas('classes', fn($query) => $query->where('class_id', $classId))->get();

        if ($students->isEmpty()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No students found in the assigned class.');
        }

        // Fetch marks for the given class, subject, exam type, and academic year
        $marks = MarkModel::with('student')
            ->where('class_id', $classId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->where('academic_year_id', $yearId)
            ->where('subject_id', $subjectId)
            ->get();

        // Fetch examinations for the syllabus and class
        $examinations = ExamModel::where('academic_year_id', $yearId)
            ->where('syllabus_id', $syllabusId)
            ->get();

        // Check for available PPT and PAT exams
        $examPPT = $this->getAvailableExam($syllabus->id, 1, $yearId);
        $examPAT = $this->getAvailableExam($syllabus->id, 2, $yearId);

        // Pass additional details for breadcrumb and exam availability
        $breadcrumbData = [
            'examTypeName' => $examType->exam_type_name,
            'syllabusName' => $syllabus->syllabus_name,
            'className'    => $class->name,
            'subjectName'  => $subject->subject_name,
        ];

        return view('teacher.examData.marks', compact(
            'marks',
            'students',
            'yearId',
            'selectedAcademicYear',
            'examType',
            'syllabus',
            'subject',
            'class',
            'breadcrumbData',
            'examinations',
            'examPPT',
            'examPAT'
        ));
    }

    /**
     * View For User Teacher
     * Updates marks and statuses for students in a specific subject, class, and exam type.
     *
     * Validates the incoming request, processes marks and attendance statuses, and updates the database accordingly.
     * Logs the processing details for debugging and tracking purposes.
     *
     * @param Request $request The HTTP request containing student marks and statuses.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the marks view with a success message.
     */
    public function teacherSubjectClassMarkEdit(Request $request)
    {
        \Log::info('Request Parameters:', $request->all());

        // Validate incoming request
        $validatedData = $request->validate([
            'marks.*.student_id' => 'required|exists:student,id',
            'marks.*.mark'       => 'required|integer|min:0|max:100',
            'status'             => 'array',
            'status.*.*'         => 'in:present,absent', // Nested array validation
            'class_id'           => 'required|integer',
            'syllabus_id'        => 'required|integer',
            'exam_type_id'       => 'required|integer',
            'academic_year_id'   => 'required|integer',
            'subject_id'         => 'required|integer',
        ]);

        $classId        = $validatedData['class_id'];
        $syllabusId     = $validatedData['syllabus_id'];
        $examTypeId     = $validatedData['exam_type_id'];
        $academicYearId = $validatedData['academic_year_id'];
        $subjectId      = $validatedData['subject_id'];
        $marks          = $validatedData['marks'];
        $statuses       = $request->input('status', []);

        foreach ($marks as $markData) {
            $studentId = $markData['student_id'];
            $status    = $statuses[$studentId][$subjectId] ?? 'present'; // Retrieve status by student and subject

            \Log::info("Processing student ID: {$studentId}, Subject ID: {$subjectId}", [
                'status' => $status,
                'mark'   => $markData['mark'],
            ]);

            $finalMark = ($status === 'absent') ? 0 : $markData['mark'];

            MarkModel::updateOrCreate(
                [
                    'student_id'       => $studentId,
                    'subject_id'       => $subjectId,
                    'class_id'         => $classId,
                    'syllabus_id'      => $syllabusId,
                    'exam_type_id'     => $examTypeId,
                    'academic_year_id' => $academicYearId,
                ],
                [
                    'mark'   => $finalMark,
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('teacher.exams.marks', [
            'yearId'     => $academicYearId,
            'examTypeId' => $examTypeId,
            'syllabusId' => $syllabusId,
            'subjectId'  => $subjectId,
            'classId'    => $classId,
        ])->with('success', 'Marks updated successfully!');
    }

    /**
     * Retrieves the first available exam of a specific type for a given syllabus and academic year.
     *
     * @param int $syllabusId       The ID of the syllabus.
     * @param int $examTypeId       The ID of the exam type (e.g., PPT, PAT).
     * @param int $yearId           The ID of the academic year.
     *
     * @return ExamModel|null       The first available exam or null if none exists.
     */
    private function getAvailableExam($syllabusId, $examTypeId, $yearId)
    {
        return ExamModel::where('academic_year_id', $yearId)
            ->where('syllabus_id', $syllabusId)
            ->where('exam_type_id', $examTypeId)
            ->where('status', 'available')
            ->first();
    }

    /**
     * Generates an exam report for the class assigned to a teacher.
     *
     * Retrieves and validates data for the specified academic year, exam type, syllabus, and exam.
     * Fetches students, marks, summaries, and subjects associated with the class and prepares data for reporting.
     *
     * @param int|null $yearId       The ID of the academic year (if null, retrieves from session).
     * @param int $examTypeId        The ID of the exam type.
     * @param int $syllabusId        The ID of the syllabus.
     * @param int $examId            The ID of the exam.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function classExamReportClassTeacher($yearId = null, $examTypeId, $syllabusId, $examId)
    {
        $teacherId            = auth()->id();
        $yearId               = session('academic_year_id'); // Use session year ID if no parameter provided
        $selectedAcademicYear = AcademicYearModel::find($yearId);

        if (! $selectedAcademicYear) {
            return redirect()->back()->with('error', 'No academic year is selected or available.');
        }

        $syllabus = SyllabusModel::find($syllabusId);
        $examType = ExamTypeModel::select('id', 'exam_type_name')->find($examTypeId);
        $exams    = ExamModel::where('academic_year_id', $yearId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->get()
            ->keyBy('syllabus_id'); // Key exams by syllabus_id for easy lookup

        $exams2 = ExamModel::where('academic_year_id', $yearId)
            ->where('exam_type_id', $examTypeId)
            ->where('syllabus_id', $syllabusId)
            ->first(); // Retrieve the first matching exam

        $exam = $exams2; // Assign single exam object

        if (! $syllabus || ! $examType || ! $exams) {
            return redirect()->back()->with('error', 'Required data (syllabus, exam type, or exam) is not available.');
        }

        $classTeacherYear = ClassTeacherYearModel::with(['class.students'])
            ->where('teacher_id', $teacherId)
            ->where('academic_year_id', $yearId)
            ->first();

        if (! $classTeacherYear || ! $classTeacherYear->class) {
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
            'subjects',
            'exam'
        ));
    }

    /**
     * Displays the form for writing or editing a summary for a specific student.
     *
     * Fetches the academic year, exam type, syllabus, student details, marks, and existing summary.
     * Prepares the data for the summary writing view.
     *
     * @param int $yearId        The ID of the academic year.
     * @param int $examTypeId    The ID of the exam type.
     * @param int $syllabusId    The ID of the syllabus.
     * @param int $examId        The ID of the exam.
     * @param int $classId       The ID of the class.
     * @param int $studentId     The ID of the student.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function writeSummaryClassTeacher($yearId, $examTypeId, $syllabusId, $examId, $classId, $studentId)
    {
        // Fetch academic year and check validity
        $selectedAcademicYear = AcademicYearModel::find($yearId);
        if (! $selectedAcademicYear) {
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

    /**
     * Handles the submission of the summary form for a specific student.
     *
     * Validates the input summary and updates or creates the student's summary in the database.
     * Redirects to the class exam report page on success or back with an error on failure.
     *
     * @param Request $request   The HTTP request containing the summary input.
     * @param int $yearId        The ID of the academic year.
     * @param int $examTypeId    The ID of the exam type.
     * @param int $syllabusId    The ID of the syllabus.
     * @param int $examId        The ID of the exam.
     * @param int $classId       The ID of the class.
     * @param int $studentId     The ID of the student.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function writeSummaryClassTeacherPost(Request $request, $yearId, $examTypeId, $syllabusId, $examId, $classId, $studentId)
    {
        $request->validate([
            'summary' => 'nullable|string|max:500',
        ]);

        try {
            // Update or create student summary
            StudentSummaryModel::updateOrCreate(
                [
                    'exam_id'    => $examId,
                    'class_id'   => $classId,
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

    /**
     * Generates a PDF report of the student's position within their class for a specific exam.
     *
     * Fetches the necessary data including the student, class, academic year, and summaries.
     * Uses the summaries to generate a position report and streams the PDF.
     *
     * @param int $yearId        The ID of the academic year.
     * @param int $examTypeId    The ID of the exam type.
     * @param int $syllabusId    The ID of the syllabus.
     * @param int $classId       The ID of the class.
     * @param int $examId        The ID of the exam.
     * @param int $studentId     The ID of the student.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function positionInClassReport($yearId, $examTypeId, $syllabusId, $classId, $examId, $studentId)
    {
        try {
            $student = StudentModel::findOrFail($studentId);
            $year    = AcademicYearModel::findOrFail($yearId);
            $class   = ClassModel::findOrFail($classId); // Ensure $class is fetched here

            // Fetch all summaries for the class based on the exam ID
            $studentSummaries = $this->summaryRepository->getSummariesAscending($examId, $classId);

            if (! $studentSummaries) {
                abort(404, 'Student summaries not available.');
            }

            // Prepare the data for the view
            $data = [
                'class'            => $class, // Make sure to include this
                'year'             => $year,
                'studentSummaries' => $studentSummaries, // Changed from 'studentSummary' to 'studentSummaries'
            ];

            // Load the view with the appropriate data
            $pdf = PDF::loadView('admin.examManagement.exams.marks.class-position', $data);
            return $pdf->stream('position_in_class_report_' . $student->full_name . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error generating report: ' . $e->getMessage());
        }
    }

    /**
     * Generates a PDF report of the student's position within their year level for a specific exam.
     *
     * Fetches the necessary data including the student, class, academic year, and summaries.
     * Uses the summaries to generate a year-level position report and streams the PDF.
     *
     * @param int $yearId        The ID of the academic year.
     * @param int $examTypeId    The ID of the exam type.
     * @param int $syllabusId    The ID of the syllabus.
     * @param int $classId       The ID of the class.
     * @param int $examId        The ID of the exam.
     * @param int $studentId     The ID of the student.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function positionInYearLevelReport($yearId, $examTypeId, $syllabusId, $classId, $examId, $studentId)
    {
        try {
            $student = StudentModel::findOrFail($studentId);
            $year    = AcademicYearModel::findOrFail($yearId);
            $class   = ClassModel::findOrFail($classId); // Ensure $class is fetched here

            // Fetch all summaries for the class based on the exam ID
            $studentSummaries = $this->summaryRepository->getSummariesAscending($examId, $classId);

            if (! $studentSummaries) {
                abort(404, 'Student summaries not available.');
            }

            // Prepare the data for the view
            $data = [
                'class'            => $class, // Make sure to include this
                'year'             => $year,
                'studentSummaries' => $studentSummaries, // Changed from 'studentSummary' to 'studentSummaries'
            ];

            // Load the view with the appropriate data
            $pdf = PDF::loadView('admin.examManagement.exams.marks.year-level-position', $data);
            return $pdf->stream('Position_in_Year_Level_Report_' . $student->full_name . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error generating report: ' . $e->getMessage());
        }
    }

}
