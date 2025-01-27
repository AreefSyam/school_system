<?php
namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\ClassTeacherYearModel;
use App\Models\ExamTypeModel;
use App\Models\GradeLevelModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\SyllabusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{
    /**
     * Analyzes and displays subject performance across various metrics.
     * Filters data based on academic year, syllabus, grade levels, subject, and exam type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function subjectPerformance(Request $request)
    {
        // Fetch filter values from the request
        $year        = $request->input('academic_year_id');
        $syllabus    = $request->input('syllabus_id');
        $gradeLevels = $request->input('grade_level_id', []); // Handle grade levels as an array
        $subject     = $request->input('subject_id');
        $examType    = $request->input('exam_type_id');

                           // Initialize the query builder but fetch data only if filters are applied
        $data = collect(); // Empty collection by default

        if ($year || $syllabus || ! empty($gradeLevels) || $subject || $examType) {
            $data = DB::table('marks as m')
                ->join('class as c', 'm.class_id', '=', 'c.id')
                ->join('grade_level as g', 'c.grade_level_id', '=', 'g.id')
                ->join('subject as s', 'm.subject_id', '=', 's.id')
                ->join('academic_year as ay', 'm.academic_year_id', '=', 'ay.id')
                ->join('student as st', 'm.student_id', '=', 'st.id')
                ->select(
                    'ay.academic_year_name',
                    'g.grade_name',
                    's.subject_name',
                    // Count for A, B, C, D, TH
                    DB::raw('COUNT(CASE WHEN m.mark >= 80 THEN 1 END) as count_A'),
                    DB::raw('COUNT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN 1 END) as count_B'),
                    DB::raw('COUNT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN 1 END) as count_C'),
                    DB::raw('COUNT(CASE WHEN m.mark < 40 AND m.status = "present" THEN 1 END) as count_D'),
                    DB::raw('COUNT(CASE WHEN m.mark = 0 AND m.status = "absent" THEN 1 END) as count_TH'),
                    // Get the student name list based on the count group
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 80 THEN st.full_name END SEPARATOR ", ") as list_A'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN st.full_name END SEPARATOR ", ") as list_B'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN st.full_name END SEPARATOR ", ") as list_C'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark < 40 AND m.status = "present" THEN st.full_name END SEPARATOR ", ") as list_D'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark = 0 AND m.status = "absent" THEN st.full_name END SEPARATOR ", ") as list_TH'),
                )
                ->when($year, function ($query, $year) {
                    $query->where('m.academic_year_id', $year);
                })
                ->when($syllabus, function ($query, $syllabus) {
                    $query->where('m.syllabus_id', $syllabus);
                })
                ->when(! empty($gradeLevels), function ($query) use ($gradeLevels) {
                    $query->whereIn('c.grade_level_id', $gradeLevels); // Use whereIn for multiple grade levels
                })
                ->when($subject, function ($query, $subject) {
                    $query->where('m.subject_id', $subject);
                })
                ->when($examType, function ($query, $examType) {
                    $query->where('m.exam_type_id', $examType);
                })
                ->groupBy('ay.academic_year_name', 'g.grade_name', 's.subject_name')
                ->paginate(6);
        }

        // Fetch all filter options for the dropdowns
        $subjects      = SubjectModel::select('id', 'subject_name')->get();
        $gradeLevels   = GradeLevelModel::select('id', 'grade_name')->get();
        $academicYears = AcademicYearModel::select('id', 'academic_year_name')->get();
        $classes       = ClassModel::select('id', 'name')->get();
        $examTypes     = ExamTypeModel::select('id', 'exam_type_name')->get();
        $syllabuses    = SyllabusModel::select('id', 'syllabus_name')->get();

        // Return the view with the data and filter options
        return view('admin.analyticManagement.bySubject.subjectAnalytic', compact(
            'data', 'subjects', 'syllabuses', 'gradeLevels', 'academicYears', 'examTypes'
        ));
    }

    /**
     * Displays class performance for selected filters.
     * Filters performance data by academic year, class, exam type, and syllabus.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function classPerformance(Request $request)
    {
        // Fetch filter values from the request
        $year     = $request->input('academic_year_id');
        $class    = $request->input('class_id'); // Directly use class_id
        $examType = $request->input('exam_type_id');
        $syllabus = $request->input('syllabus_id');

                           // Initialize the query builder but fetch data only if filters are applied
        $data = collect(); // Empty collection by default

        // Fetch individual student performance data
        if ($year || $class || $syllabus || $examType) {
            // Query to get performance counts for each subject in the selected class
            $data = DB::table('marks as m')
                ->join('subject as s', 'm.subject_id', '=', 's.id')               // Join subject for subject name
                ->join('academic_year as ay', 'm.academic_year_id', '=', 'ay.id') // Join academic year for year name
                ->join('class as c', 'm.class_id', '=', 'c.id')                   // Join class table for class name
                ->join('student as st', 'm.student_id', '=', 'st.id')
                ->select(
                    'ay.academic_year_name', // Fetch academic year name
                    'c.name as class_name',  // Fetch class name
                    's.subject_name',        // Fetch subject name
                                             // Count for A, B, C, D, TH
                    DB::raw('COUNT(CASE WHEN m.mark >= 80 THEN 1 END) as count_A'),
                    DB::raw('COUNT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN 1 END) as count_B'),
                    DB::raw('COUNT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN 1 END) as count_C'),
                    DB::raw('COUNT(CASE WHEN m.mark < 40 AND m.status = "present" THEN 1 END) as count_D'),
                    DB::raw('COUNT(CASE WHEN m.mark = 0 AND m.status = "absent" THEN 1 END) as count_TH'),
                    // Get the student name list based on the count group
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 80 THEN st.full_name END SEPARATOR ", ") as list_A'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN st.full_name END SEPARATOR ", ") as list_B'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN st.full_name END SEPARATOR ", ") as list_C'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark < 40 AND m.status = "present" THEN st.full_name END SEPARATOR ", ") as list_D'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark = 0 AND m.status = "absent" THEN st.full_name END SEPARATOR ", ") as list_TH'),
                )
                ->when($year, fn($query) => $query->where('m.academic_year_id', $year))
                ->when($class, fn($query) => $query->where('m.class_id', $class)) // Filter by class_id from marks table
                ->when($examType, fn($query) => $query->where('m.exam_type_id', $examType))
                ->when($syllabus, fn($query) => $query->where('m.syllabus_id', $syllabus))
                ->groupBy('ay.academic_year_name', 'c.name', 's.subject_name') // Group by class name
                ->get();
        }

        // Fetch dropdown data
        $academicYears = AcademicYearModel::select('id', 'academic_year_name')->get();
        $classes       = ClassModel::select('id', 'name')->get();
        $examTypes     = ExamTypeModel::select('id', 'exam_type_name')->get();
        $syllabuses    = SyllabusModel::select('id', 'syllabus_name')->get();

        // Return the view
        return view('admin.analyticManagement.byClass.classAnalytic', compact(
            'data', 'academicYears', 'classes', 'examTypes', 'syllabuses',
        ));
    }

    /**
     * Displays individual student performance for selected filters.
     * Filters performance data by academic year, class, syllabus, and student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function individualPerformance(Request $request)
    {
        // Fetch filter values from the request
        $year     = $request->input('academic_year_id');
        $class    = $request->input('class_id');
        $syllabus = $request->input('syllabus_id');
        $student  = $request->input('student_id');

                           // Initialize the query builder but fetch data only if filters are applied
        $data = collect(); // Empty collection by default

        if ($year || $class || $syllabus || $student) {
            $data = DB::table('marks as m')
                ->join('subject as s', 'm.subject_id', '=', 's.id')
                ->join('class as c', 'm.class_id', '=', 'c.id')
                ->join('academic_year as ay', 'm.academic_year_id', '=', 'ay.id')
                ->join('student as st', 'm.student_id', '=', 'st.id')
                ->select(
                    'm.student_id',
                    'm.subject_id',
                    'm.mark',
                    'm.status',
                    'st.full_name as student_name',
                    's.subject_name',
                    'ay.academic_year_name',
                    'c.name as class_name',
                    'm.exam_type_id'
                )
                ->when($year, function ($query) use ($year) {
                    return $query->where('m.academic_year_id', $year);
                })
                ->when($class, function ($query) use ($class) {
                    return $query->where('m.class_id', $class);
                })
                ->when($syllabus, function ($query) use ($syllabus) {
                    return $query->where('m.syllabus_id', $syllabus);
                })
                ->when($student, function ($query) use ($student) {
                    return $query->where('m.student_id', $student);
                })
                ->get();
        }

        // Pre-organize data into a nested associative array
        $marksByStudent = [];
        foreach ($data as $entry) {
            $examType = $entry->exam_type_id === 1 ? 'PPT' : 'PAT';
            // $marksByStudent[$entry->student_id][$entry->subject_id][$examType] = $entry->mark;
            $marksByStudent[$entry->student_id][$entry->subject_id][$examType] =
            $entry->status === 'absent' ? 'TH' : $entry->mark;
        }

        // Prepare data for the chart
        $chartData = $data->groupBy('exam_type_id')->map(function ($group) {
            // return $group->pluck('mark', 'subject_name');
            return $group->pluck('mark', 'subject_name')->filter(function ($mark) {
                return is_numeric($mark); // Exclude "TH" from chart data
            });
        });

        // Fetch dropdown data with applied filters
        $academicYears = AcademicYearModel::select('id', 'academic_year_name')->get();
        $classes       = ClassModel::select('id', 'name')->get();
        $syllabuses    = SyllabusModel::select('id', 'syllabus_name')->get();
        $students      = StudentModel::when($student, function ($query) use ($student) {
            return $query->where('id', $student); // Filter to the selected student
        })->get();

        // Fetch subjects filtered by the selected syllabus and active status
        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('subject_grade.active', 1) // Only active records
            ->when($syllabus, function ($query) use ($syllabus) {
                return $query->where('subject.syllabus_id', $syllabus);
            })
            ->when($year, function ($query) use ($year) {
                return $query->where('subject.academic_year_id', $year);
            })
            ->where('subject_grade.grade_level_id', $class) // Match class's grade level
            ->select('subject.id', 'subject.subject_name')
            ->get();

        // Return view with data
        return view('admin.analyticManagement.byIndividual.individualAnalytic', compact(
            'data', 'academicYears', 'classes', 'syllabuses', 'students', 'subjects', 'marksByStudent', 'chartData'
        ));
    }

    /**
     * Retrieves and displays a report of students who scored below 61% in their exams.
     * Filters the report based on academic year, class, syllabus, and exam type.
     * Additional details include subjects where students failed or were absent.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     *
     * Method filters:
     * - Academic year, class, syllabus, and exam type are used to narrow down the results.
     * - Only students with a total percentage below 61% are included in the report.
     * - The report shows subjects where students scored below 40% or were absent.
     *
     * Outputs:
     * - Generates a list of students along with their performance metrics.
     * - Provides options for filters to help refine the search results.
     */
    public function reportStudentLess60Percent(Request $request)
    {
        // Fetch filter values from the request
        $year     = $request->input('academic_year_id');
        $class    = $request->input('class_id');
        $syllabus = $request->input('syllabus_id');
        $examType = $request->input('exam_type_id');

        // Initialize an empty collection for $data
        $data = collect();

        // Fetch students below 61% if filters are applied
        if ($year || $class || $syllabus || $examType) {
            $data = DB::table('students_summary as ss')
                ->join('student as st', 'ss.student_id', '=', 'st.id')
                ->join('class as c', 'ss.class_id', '=', 'c.id')
                ->join('academic_year as ay', 'ss.academic_year_id', '=', 'ay.id')
                ->leftJoin('marks as m', function ($join) {
                    $join->on('ss.student_id', '=', 'm.student_id')
                        ->on('ss.class_id', '=', 'm.class_id')
                        ->on('ss.academic_year_id', '=', 'm.academic_year_id');
                })
                ->join('subject as s', 'm.subject_id', '=', 's.id')
                ->join('syllabus as sy', 'ss.syllabus_id', '=', 'sy.id') // Join syllabus table
                ->select(
                    'ss.id as summary_id',
                    'st.full_name as student_name',
                    'ay.academic_year_name',
                    'c.name as class_name',
                    'ss.total_marks',
                    'ss.percentage',
                    'ss.total_grade',
                    DB::raw('GROUP_CONCAT(DISTINCT CASE
                    WHEN m.mark < 40 AND m.status = "present" AND
                         (m.academic_year_id = ss.academic_year_id AND
                          m.class_id = ss.class_id AND
                          m.syllabus_id = ss.syllabus_id AND
                          m.exam_type_id = ss.exam_type_id)
                    THEN s.subject_name ELSE NULL END) as failed_subjects'),
                    DB::raw('GROUP_CONCAT(DISTINCT CASE
                    WHEN m.status = "absent" AND
                         (m.academic_year_id = ss.academic_year_id AND
                          m.class_id = ss.class_id AND
                          m.syllabus_id = ss.syllabus_id AND
                          m.exam_type_id = ss.exam_type_id)
                    THEN s.subject_name ELSE NULL END) as absent_subjects')

                )
                ->when($year, fn($query) => $query->where('ss.academic_year_id', $year))
                ->when($class, fn($query) => $query->where('ss.class_id', $class))
                ->when($syllabus, fn($query) => $query->where('ss.syllabus_id', $syllabus))
                ->when($examType, fn($query) => $query->where('ss.exam_type_id', $examType))
                ->where('ss.percentage', '<', 61)
                ->groupBy('ss.id', 'st.full_name', 'ay.academic_year_name', 'c.name', 'ss.total_marks', 'ss.percentage', 'ss.total_grade')
                ->orderBy('ss.academic_year_id')
                ->orderBy('c.name')
                ->orderBy('ss.percentage', 'ASC')
                ->get();
        }

        // Fetch dropdown options for filters
        $academicYears = AcademicYearModel::select('id', 'academic_year_name')->get();
        $classes       = ClassModel::select('id', 'name')->get();
        $examTypes     = ExamTypeModel::select('id', 'exam_type_name')->get();
        $syllabuses    = SyllabusModel::select('id', 'syllabus_name')->get();

        // Return the view with the collected data
        return view('admin.analyticManagement.refinementClass.reportStudentLess60Percent', compact(
            'data', 'academicYears', 'classes', 'examTypes', 'syllabuses'
        ));
    }

    /** ANALYTIC TEACHER
     * [User:Teacher] -> Performance ByIndividual
     * Fetches individual performance data for the current teacher's class,
     * filtered by academic year, syllabus, and student.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function individualPerformanceTeacher(Request $request)
    {
        $teacherId            = auth()->id();                // Get Teacher ID
        $yearId               = session('academic_year_id'); // Get the selected academic year from the session
        $selectedAcademicYear = AcademicYearModel::find($yearId);

        // Check if the selected academic year is valid
        if (! $selectedAcademicYear) {
            // Return view with error but stay on the same page
            return view('teacher.analyticTeacher.byIndividual.individualAnalytic', [
                'error'                => 'The selected academic year is invalid or missing.',
                'data'                 => collect(), // Empty collection
                'students'             => collect(),
                'syllabuses'           => collect(),
                'marksByStudent'       => [],
                'chartData'            => collect(),
                'class'                => null,
                'subjects'             => collect(),
                'selectedAcademicYear' => null,
            ]);
        }

        // Fetch the class assigned to the teacher for the selected academic year
        $classTeacherYear = ClassTeacherYearModel::with('class')
            ->where('teacher_id', $teacherId)
            ->where('academic_year_id', $yearId)
            ->first();

        // Handle missing class assignment
        if (! $classTeacherYear || ! $classTeacherYear->class) {
            return view('teacher.analyticTeacher.byIndividual.individualAnalytic', [
                'error'                => 'No class is assigned to you for the selected academic year.',
                'data'                 => collect(),
                'students'             => collect(),
                'syllabuses'           => collect(),
                'marksByStudent'       => [],
                'chartData'            => collect(),
                'class'                => null,
                'subjects'             => collect(),
                'selectedAcademicYear' => $selectedAcademicYear,
            ]);
        }

        $class = $classTeacherYear->class;

        // Fetch filter values from the request
        $syllabusId = $request->input('syllabus_id');
        $studentId  = $request->input('student_id');

        // Initialize the query builder but fetch data only if filters are applied
        $data = collect();

        if ($syllabusId || $studentId) {
            $data = DB::table('marks as m')
                ->join('subject as s', 'm.subject_id', '=', 's.id')
                ->join('class as c', 'm.class_id', '=', 'c.id')
                ->join('academic_year as ay', 'm.academic_year_id', '=', 'ay.id')
                ->join('student as st', 'm.student_id', '=', 'st.id')
                ->select(
                    'm.student_id',
                    'm.subject_id',
                    'm.mark',
                    'm.status',
                    'st.full_name as student_name',
                    's.subject_name',
                    'ay.academic_year_name',
                    'c.name as class_name',
                    'm.exam_type_id'
                )
                ->where('m.class_id', $class->id) // Restrict to the teacher's assigned class
                ->where('m.academic_year_id', $yearId)
                ->when($syllabusId, function ($query) use ($syllabusId) {
                    return $query->where('m.syllabus_id', $syllabusId);
                })
                ->when($studentId, function ($query) use ($studentId) {
                    return $query->where('m.student_id', $studentId);
                })
                ->get();
        }

        // Pre-organize data into a nested associative array
        $marksByStudent = [];
        foreach ($data as $entry) {
            $examType = $entry->exam_type_id === 1 ? 'PPT' : 'PAT';
            // $marksByStudent[$entry->student_id][$entry->subject_id][$examType] = $entry->mark;
            $marksByStudent[$entry->student_id][$entry->subject_id][$examType] =
            $entry->status === 'absent' ? 'TH' : $entry->mark;
        }

        // Prepare data for the chart
        $chartData = $data->groupBy('exam_type_id')->map(function ($group) {
            // return $group->pluck('mark', 'subject_name');
            return $group->pluck('mark', 'subject_name')->filter(function ($mark) {
                return is_numeric($mark); // Exclude "TH" from chart data
            });
        });

        // Fetch dropdown data
        $syllabuses = SyllabusModel::select('id', 'syllabus_name')->get();
        $students   = StudentModel::whereHas('classes', function ($query) use ($class) {
            $query->where('class_id', $class->id);
        })
            ->when($studentId, function ($query) use ($studentId) {
                return $query->where('id', $studentId);
            })
            ->get();

        // Fetch subjects filtered by the selected syllabus and active status
        $subjects = DB::table('subject')
            ->join('subject_grade', 'subject.id', '=', 'subject_grade.subject_id')
            ->where('subject_grade.active', 1) // Only active records
            ->when($syllabusId, function ($query) use ($syllabusId) {
                return $query->where('subject.syllabus_id', $syllabusId);
            })
            ->when($yearId, function ($query) use ($yearId) {
                return $query->where('subject.academic_year_id', $yearId);
            })
            ->where('subject_grade.grade_level_id', $class->grade_level_id) // Match class's grade level
            ->select('subject.id', 'subject.subject_name')
            ->get();

        // Return the view with data
        return view('teacher.analyticTeacher.byIndividual.individualAnalytic', compact(
            'data',
            'students',
            'syllabuses',
            'marksByStudent',
            'chartData',
            'class',
            'subjects',
            'selectedAcademicYear'
        ));
    }

    /**
     * [User:Teacher] -> Performance ByCLass
     * Fetches class performance data for the current teacher's class,
     * filtered by exam type and syllabus.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function classPerformanceTeacher(Request $request)
    {
        $teacherId            = auth()->id();                // Get Teacher ID
        $yearId               = session('academic_year_id'); // Get the selected academic year from the session
        $selectedAcademicYear = AcademicYearModel::find($yearId);

        // Validate academic year existence
        if (! $selectedAcademicYear) {
            // Return view with error but stay on the same page
            return view('teacher.analyticTeacher.byClass.classAnalytic', [
                'error'                => 'No class is assigned to you for the selected academic year.',
                'data'                 => collect(), // Empty collection
                'examTypes'            => collect(),
                'syllabuses'           => collect(),
                'selectedAcademicYear' => $selectedAcademicYear,
                'class'                => null,
            ]);
        }

        // Fetch the class assigned to the teacher for the selected academic year
        $classTeacherYear = ClassTeacherYearModel::with('class')
            ->where('teacher_id', $teacherId)
            ->where('academic_year_id', $yearId)
            ->first();

        // Handle missing class assignment
        if (! $classTeacherYear || ! $classTeacherYear->class) {
            return view('teacher.analyticTeacher.byClass.classAnalytic', [
                'error'                => 'No class is assigned to you for the selected academic year.',
                'data'                 => collect(),
                'examTypes'            => collect(),
                'syllabuses'           => collect(),
                'selectedAcademicYear' => $selectedAcademicYear,
                'class'                => null,
            ]);
        }

        $class = $classTeacherYear->class;

        // Fetch filter values from the request
        $examTypeId = $request->input('exam_type_id');
        $syllabusId = $request->input('syllabus_id');

        // Initialize the query builder but fetch data only if filters are applied
        $data = collect();

        if ($examTypeId || $syllabusId) {
            $data = DB::table('marks as m')
                ->join('subject as s', 'm.subject_id', '=', 's.id')               // Join subject for subject name
                ->join('academic_year as ay', 'm.academic_year_id', '=', 'ay.id') // Join academic year for year name
                ->join('class as c', 'm.class_id', '=', 'c.id')                   // Join class table for class name
                ->join('student as st', 'm.student_id', '=', 'st.id')
                ->select(
                    'ay.academic_year_name', // Fetch academic year name
                    'c.name as class_name',  // Fetch class name
                    's.subject_name',        // Fetch subject name
                    // Count for A, B, C, D, TH
                    DB::raw('COUNT(CASE WHEN m.mark >= 80 THEN 1 END) as count_A'),
                    DB::raw('COUNT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN 1 END) as count_B'),
                    DB::raw('COUNT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN 1 END) as count_C'),
                    DB::raw('COUNT(CASE WHEN m.mark < 40 AND m.status = "present" THEN 1 END) as count_D'),
                    DB::raw('COUNT(CASE WHEN m.mark = 0 AND m.status = "absent" THEN 1 END) as count_TH'),
                    // Get the student name list based on the count group
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 80 THEN st.full_name END SEPARATOR ", ") as list_A'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN st.full_name END SEPARATOR ", ") as list_B'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN st.full_name END SEPARATOR ", ") as list_C'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark < 40 AND m.status = "present" THEN st.full_name END SEPARATOR ", ") as list_D'),
                    DB::raw('GROUP_CONCAT(CASE WHEN m.mark = 0 AND m.status = "absent" THEN st.full_name END SEPARATOR ", ") as list_TH'),
                )
                ->where('m.class_id', $class->id) // Restrict to the teacher's assigned class
                ->where('m.academic_year_id', $yearId)
                ->when($examTypeId, function ($query) use ($examTypeId) {
                    return $query->where('m.exam_type_id', $examTypeId);
                })
                ->when($syllabusId, function ($query) use ($syllabusId) {
                    return $query->where('m.syllabus_id', $syllabusId);
                })
                ->groupBy('ay.academic_year_name', 'c.name', 's.subject_name') // Group by class name
                ->get();
        }

        // Fetch dropdown data
        $examTypes  = ExamTypeModel::select('id', 'exam_type_name')->get();
        $syllabuses = SyllabusModel::select('id', 'syllabus_name')->get();

        // Return the view with data
        return view('teacher.analyticTeacher.byClass.classAnalytic', compact(
            'data',
            'examTypes',
            'syllabuses',
            'class',
            'selectedAcademicYear',
        ));
    }

    /**
     * Provides a report of students scoring less than 60% in the current teacher's class,
     * filtered by exam type and syllabus.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function reportStudentLess60PercentTeacher(Request $request)
    {
        $teacherId            = auth()->id();                // Logged-in teacher's ID
        $yearId               = session('academic_year_id'); // Academic year from session
        $selectedAcademicYear = AcademicYearModel::find($yearId);

        // Initialize the query builder but fetch data only if filters are applied
        $data = collect();

        // Validate academic year existence
        if (! $selectedAcademicYear) {
            return view('teacher.analyticTeacher.refinementClass.reportStudentLess60Percent', [
                'error'                => 'No academic year is currently selected.',
                'data'                 => collect(), // Empty collection
                'examTypes'            => collect(),
                'syllabuses'           => collect(),
                'selectedAcademicYear' => null,
            ]);
        }

        // Fetch the teacher's assigned class for the academic year
        $classTeacherYear = ClassTeacherYearModel::with('class')
            ->where('teacher_id', $teacherId)
            ->where('academic_year_id', $yearId)
            ->first();

        if (! $classTeacherYear || ! $classTeacherYear->class) {
            return view('teacher.analyticTeacher.refinementClass.reportStudentLess60Percent', [
                'error'                => 'No class is assigned to you for the selected academic year.',
                'data'                 => collect(),
                'examTypes'            => collect(),
                'syllabuses'           => collect(),
                'selectedAcademicYear' => $selectedAcademicYear,
            ]);
        }

        $class = $classTeacherYear->class;

        // Fetch filter values from the request
        $examTypeId = $request->input('exam_type_id');
        $syllabusId = $request->input('syllabus_id');

        // Query for students below 61% only if filters are applied
        if ($examTypeId || $syllabusId) {
            $data = DB::table('students_summary as ss')
                ->join('student as st', 'ss.student_id', '=', 'st.id')
                ->join('class as c', 'ss.class_id', '=', 'c.id')
                ->join('academic_year as ay', 'ss.academic_year_id', '=', 'ay.id')
                ->leftJoin('marks as m', function ($join) {
                    $join->on('ss.student_id', '=', 'm.student_id')
                        ->on('ss.class_id', '=', 'm.class_id')
                        ->on('ss.academic_year_id', '=', 'm.academic_year_id');
                })
                ->join('subject as s', 'm.subject_id', '=', 's.id')
                ->select(
                    'ss.id as summary_id',
                    'st.full_name as student_name',
                    'ay.academic_year_name',
                    'c.name as class_name',
                    'ss.total_marks',
                    'ss.percentage',
                    'ss.total_grade',
                    DB::raw('GROUP_CONCAT(DISTINCT CASE
                    WHEN m.mark < 40 AND m.status = "present" AND
                         (m.academic_year_id = ss.academic_year_id AND
                          m.class_id = ss.class_id AND
                          m.exam_type_id = ss.exam_type_id AND
                          m.syllabus_id = ss.syllabus_id)
                    THEN s.subject_name ELSE NULL END) as failed_subjects'),
                    DB::raw('GROUP_CONCAT(DISTINCT CASE
                    WHEN m.status = "absent" AND
                         (m.academic_year_id = ss.academic_year_id AND
                          m.class_id = ss.class_id AND
                          m.exam_type_id = ss.exam_type_id AND
                          m.syllabus_id = ss.syllabus_id)
                    THEN s.subject_name ELSE NULL END) as absent_subjects')
                )
                ->where('ss.class_id', $class->id)
                ->where('ss.academic_year_id', $yearId)
                ->when($examTypeId, fn($query) => $query->where('ss.exam_type_id', $examTypeId))
                ->when($syllabusId, fn($query) => $query->where('ss.syllabus_id', $syllabusId))
                ->where('ss.percentage', '<', 61)
                ->groupBy('ss.id', 'st.full_name', 'ay.academic_year_name', 'c.name', 'ss.total_marks', 'ss.percentage', 'ss.total_grade')
                ->orderBy('ss.percentage', 'ASC')
                ->get();
        }

        $examTypes  = ExamTypeModel::select('id', 'exam_type_name')->get();
        $syllabuses = SyllabusModel::select('id', 'syllabus_name')->get();

        return view('teacher.analyticTeacher.refinementClass.reportStudentLess60Percent', compact(
            'data',
            'examTypes',
            'syllabuses',
            'selectedAcademicYear',
            'class'
        ));
    }

}
