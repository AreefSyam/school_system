<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ExamTypeModel;
use App\Models\GradeLevelModel;
use App\Models\SubjectModel;
use App\Models\SyllabusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{
    // public function byGrade(Request $request)
    // {
    //     // Fetch filter values from the request
    //     $grades = $request->input('grades', ['1', '2', '3', '4', '5', '6']); // Default grades
    //     $subject = $request->input('subject');
    //     $year = $request->input('year');

    //     // Fetch data based on filters
    // $data = StudentSummaryModel::query()
    // ->select(
    //     'class_id',
    //     DB::raw('COUNT(student_id) as total_students'),
    //     DB::raw('SUM(CASE WHEN total_grade LIKE "%A%" THEN 1 ELSE 0 END) as A'),
    //     DB::raw('SUM(CASE WHEN total_grade LIKE "%B%" THEN 1 ELSE 0 END) as B'),
    //     DB::raw('SUM(CASE WHEN total_grade LIKE "%C%" THEN 1 ELSE 0 END) as C'),
    //     DB::raw('SUM(CASE WHEN total_grade LIKE "%D%" THEN 1 ELSE 0 END) as D')
    // )
    // ->whereIn('class_id', $grades)
    // ->when($year, fn($query) => $query->where('academic_year_id', $year))
    // ->when($subject, fn($query) => $query->whereHas('marks', fn($q) => $q->where('subject_id', $subject)))
    // ->groupBy('class_id')
    // ->get();

    //     // Pass data to the view
    //     return view('admin.analyticManagement.byGrade.grade', compact('data', 'year', 'syllabus', 'examType', 'subject', 'grades'));
    // }

    // public function gradePerformance(Request $request)
    // {
    //     // Get filters from the request (optional)
    //     $year = $request->input('year', null); // Filter by year
    //     $syllabus = $request->input('syllabus', null); // Filter by syllabus
    //     $examType = $request->input('examType', null); // Filter by exam type

    //     // Query the database to fetch grade performance data
    //     $data = StudentSummaryModel::query()
    //         ->join('marks', 'student_summaries.student_id', '=', 'marks.student_id') // Join with marks table
    //         ->select(
    //             'class_id',
    //             'marks.subject_id',
    //             DB::raw('AVG(marks.mark) as average_mark') // Calculate average marks
    //         )
    //         ->when($year, fn($query) => $query->where('student_summaries.academic_year_id', $year))
    //         ->when($syllabus, fn($query) => $query->where('student_summaries.syllabus_id', $syllabus))
    //         ->when($examType, fn($query) => $query->where('student_summaries.exam_type_id', $examType))
    //         ->groupBy('class_id', 'marks.subject_id') // Group by class and subject
    //         ->get();

    //     // Pass the data to the view
    //     return view('admin.analyticManagement.byGrade.grade', compact('data', 'year', 'syllabus', 'examType'));
    // }

    // public function gradePerformance(Request $request)
    // {
    //     // Get filters from the request (optional)
    //     $year = $request->input('year', null); // Filter by year
    //     $syllabus = $request->input('syllabus', null); // Filter by syllabus
    //     $examType = $request->input('examType', null); // Filter by exam type

    //     // Query the database to fetch grade performance data
    //     $data = StudentSummaryModel::query()
    //         ->join('marks', 'students_summary.student_id', '=', 'marks.student_id') // Join with marks table
    //         ->select(
    //             'students_summary.class_id', // Explicitly specify the table for class_id
    //             'marks.subject_id',
    //             DB::raw('AVG(marks.mark) as average_mark') // Calculate average marks
    //         )
    //         ->when($year, fn($query) => $query->where('student_summaries.academic_year_id', $year))
    //         ->when($syllabus, fn($query) => $query->where('student_summaries.syllabus_id', $syllabus))
    //         ->when($examType, fn($query) => $query->where('student_summaries.exam_type_id', $examType))
    //         ->groupBy('students_summary.class_id', 'marks.subject_id') // Specify the table for groupBy
    //         ->get();

    //     // Pass the data to the view
    //     return view('admin.analyticManagement.byGrade.grade', compact('data', 'year', 'syllabus', 'examType'));
    // }

    // public function gradePerformance(Request $request)
    // {
    //     // Get filters from the request (optional)
    //     $year = $request->input('year', null); // Filter by year
    //     $syllabus = $request->input('syllabus', null); // Filter by syllabus
    //     $examType = $request->input('examType', null); // Filter by exam type

    //     // Fetch all necessary data
    //     $subjects = DB::table('subject')->select('id', 'subject_name')->get(); // Fetch subjects dynamically
    //     $syllabuses = DB::table('syllabus')->select('id', 'syllabus_name')->get(); // Fetch syllabuses
    //     $gradeLevels = DB::table('grade_level')->select('id', 'grade_name')->get(); // Fetch grade levels
    //     $academicYears = DB::table('academic_year')->select('id', 'academic_year_name')->get(); // Fetch academic years
    //     $examTypes = DB::table('exam_type')->select('id', 'exam_type_name')->get(); // Fetch exam types

    //     // Query the database to fetch grade performance data
    //     $data = StudentSummaryModel::query()
    //         ->join('marks', 'students_summary.student_id', '=', 'marks.student_id') // Join with marks table
    //         ->select(
    //             'students_summary.class_id', // Explicitly specify the table for class_id
    //             'marks.subject_id',
    //             DB::raw('AVG(marks.mark) as average_mark') // Calculate average marks
    //         )
    //         ->when($year, fn($query) => $query->where('students_summary.academic_year_id', $year))
    //         ->when($syllabus, fn($query) => $query->where('students_summary.syllabus_id', $syllabus))
    //         ->when($examType, fn($query) => $query->where('students_summary.exam_type_id', $examType))
    //         ->groupBy('students_summary.class_id', 'marks.subject_id') // Specify the table for groupBy
    //         ->get();

    //     // Pass all data to the view
    //     return view('admin.analyticManagement.byGrade.grade', compact(
    //         'data', 'subjects', 'syllabuses', 'gradeLevels', 'academicYears', 'examTypes', 'year', 'syllabus', 'examType'
    //     ));
    // }

    // public function subjectPerformance(Request $request)
    // {
    //     $year = $request->input('academic_year_id');
    //     $year = $request->input('academic_year_id');
    //     $syllabus = $request->input('syllabus_id');
    //     $gradeLevel = $request->input('grade_level_id');
    //     $subject = $request->input('subject_id');
    //     $examType = $request->input('exam_type_id');

    //     // Grade thresholds
    //     $gradeThresholds = [
    //         'A' => 80,
    //         'B' => 60,
    //         'C' => 40,
    //         'D' => 0,
    //     ];

    //     $data = StudentSummaryModel::query()
    //     ->join('marks', 'students_summary.student_id', '=', 'marks.student_id')
    //     ->join('class', 'students_summary.class_id', '=', 'class.id')
    //     ->select(
    //         'students_summary.class_id',
    //         'marks.subject_id',
    //         // DB::raw('AVG(marks.mark) as average_mark'),
    //         DB::raw("
    //             SUM(CASE WHEN marks.mark >= {$gradeThresholds['A']} THEN 1 ELSE 0 END) as count_A,
    //             SUM(CASE WHEN marks.mark >= {$gradeThresholds['B']} AND marks.mark < {$gradeThresholds['A']} THEN 1 ELSE 0 END) as count_B,
    //             SUM(CASE WHEN marks.mark >= {$gradeThresholds['C']} AND marks.mark < {$gradeThresholds['B']} THEN 1 ELSE 0 END) as count_C,
    //             SUM(CASE WHEN marks.mark < {$gradeThresholds['C']} THEN 1 ELSE 0 END) as count_D
    //         ")
    //     )
    //     ->when($year, fn($query) => $query->where('students_summary.academic_year_id', $year))
    //     ->when($syllabus, fn($query) => $query->where('students_summary.syllabus_id', $syllabus))
    //     ->when($gradeLevel, fn($query) => $query->where('class.grade_level_id', $gradeLevel))
    //     ->when($subject, fn($query) => $query->where('marks.subject_id', $subject))
    //     ->when($examType, fn($query) => $query->where('students_summary.exam_type_id', $examType))
    //     ->groupBy('students_summary.class_id', 'marks.subject_id')
    //     ->get();

    //     // $data = StudentSummaryModel::query()
    //     //     ->join('marks', 'students_summary.student_id', '=', 'marks.student_id')
    //     //     ->join('class', 'students_summary.class_id', '=', 'class.id') // Join class table
    //     //     ->select(
    //     //         'students_summary.class_id',
    //     //         'marks.subject_id',
    //     //         DB::raw('AVG(marks.mark) as average_mark')
    //     //     )
    //     //     ->when($year, fn($query) => $query->where('students_summary.academic_year_id', $year))
    //     //     ->when($syllabus, fn($query) => $query->where('students_summary.syllabus_id', $syllabus))
    //     //     ->when($gradeLevel, fn($query) => $query->where('class.grade_level_id', $gradeLevel)) // Filter by grade_level_id
    //     //     ->when($subject, fn($query) => $query->where('marks.subject_id', $subject))
    //     //     ->when($examType, fn($query) => $query->where('students_summary.exam_type_id', $examType))
    //     //     ->groupBy('students_summary.class_id', 'marks.subject_id')
    //     //     ->get();

    //     $subjects = SubjectModel::all();
    //     $syllabuses = SyllabusModel::all();
    //     $gradeLevels = GradeLevelModel::all();
    //     $academicYears = AcademicYearModel::all();
    //     $examTypes = ExamTypeModel::all();

    //     return view('admin.analyticManagement.bySubject.subject', compact(
    //         'data', 'subjects', 'syllabuses', 'gradeLevels', 'academicYears', 'examTypes'
    //     ));
    // }

    // public function subjectPerformance(Request $request)
    // {
    //     $year = $request->input('academic_year_id');
    //     $syllabus = $request->input('syllabus_id');
    //     $gradeLevel = $request->input('grade_level_id');
    //     $subject = $request->input('subject_id');
    //     $examType = $request->input('exam_type_id');

    //     $data = DB::table('marks as m')
    //         ->join('class as c', 'm.class_id', '=', 'c.id') // Join with class table for grade_level_id
    //         ->join('grade_level as g', 'c.grade_level_id', '=', 'g.id') // Join with grade_level table for grade name
    //         ->join('subject as s', 'm.subject_id', '=', 's.id') // Join with subject table for subject name
    //         ->select(
    //             'g.grade_name', // Select grade name
    //             's.subject_name', // Select subject name
    //             DB::raw('SUM(CASE WHEN m.mark >= 80 THEN 1 ELSE 0 END) as count_A'),
    //             DB::raw('SUM(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN 1 ELSE 0 END) as count_B'),
    //             DB::raw('SUM(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN 1 ELSE 0 END) as count_C'),
    //             DB::raw('SUM(CASE WHEN m.mark < 40 THEN 1 ELSE 0 END) as count_D')
    //         )
    //         ->when($year, fn($query) => $query->where('m.academic_year_id', $year))
    //         ->when($syllabus, fn($query) => $query->where('m.syllabus_id', $syllabus))
    //         ->when($subject, fn($query) => $query->where('m.subject_id', $subject))
    //         ->when($examType, fn($query) => $query->where('m.exam_type_id', $examType))
    //         ->groupBy('g.grade_name', 's.subject_name') // Group by grade name and subject name
    //         ->get();

    //     // Fetch filters
    //     $subjects = SubjectModel::all();
    //     $syllabuses = SyllabusModel::all();
    //     $gradeLevels = GradeLevelModel::all();
    //     $academicYears = AcademicYearModel::all();
    //     $examTypes = ExamTypeModel::all();

    //     return view('admin.analyticManagement.bySubject.subject', compact(
    //         'data', 'subjects', 'syllabuses', 'gradeLevels', 'academicYears', 'examTypes'
    //     ));
    // }

    public function subjectPerformance(Request $request)
    {
        // Fetch filter values from the request
        $year = $request->input('academic_year_id');
        $syllabus = $request->input('syllabus_id');
        $gradeLevel = $request->input('grade_level_id');
        $subject = $request->input('subject_id');
        $examType = $request->input('exam_type_id');

        // // Query the database
        // $data = DB::table('marks as m')
        //     ->join('class as c', 'm.class_id', '=', 'c.id') // Join with class table for grade_level_id
        //     ->join('grade_level as g', 'c.grade_level_id', '=', 'g.id') // Join with grade_level table for grade name
        //     ->join('subject as s', 'm.subject_id', '=', 's.id') // Join with subject table for subject name
        //     ->select(
        //         'g.grade_name', // Select grade name
        //         's.subject_name', // Select subject name
        //         DB::raw('COUNT(CASE WHEN m.mark >= 80 THEN 1 END) as count_A'),
        //         DB::raw('COUNT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN 1 END) as count_B'),
        //         DB::raw('COUNT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN 1 END) as count_C'),
        //         DB::raw('COUNT(CASE WHEN m.mark < 40 THEN 1 END) as count_D'),
        //     )
        //     ->when($year, function ($query, $year) {
        //         $query->where('m.academic_year_id', $year);
        //     })
        //     ->when($syllabus, function ($query, $syllabus) {
        //         $query->where('m.syllabus_id', $syllabus);
        //     })
        //     ->when($gradeLevel, function ($query, $gradeLevel) {
        //         $query->where('c.grade_level_id', $gradeLevel);
        //     })
        //     ->when($subject, function ($query, $subject) {
        //         $query->where('m.subject_id', $subject);
        //     })
        //     ->when($examType, function ($query, $examType) {
        //         $query->where('m.exam_type_id', $examType);
        //     })
        //     ->groupBy('g.grade_name', 's.subject_name') // Group by grade name and subject name
        //     ->get();

        $data = DB::table('marks as m')
            ->join('class as c', 'm.class_id', '=', 'c.id') // Join with class table for grade_level_id
            ->join('grade_level as g', 'c.grade_level_id', '=', 'g.id') // Join with grade_level table for grade name
            ->join('subject as s', 'm.subject_id', '=', 's.id') // Join with subject table for subject name
            ->join('academic_year as ay', 'm.academic_year_id', '=', 'ay.id') // Join with academic_year table for year name
            ->select(
                'ay.academic_year_name', // Select year name
                'g.grade_name', // Select grade name
                's.subject_name', // Select subject name
                DB::raw('COUNT(CASE WHEN m.mark >= 80 THEN 1 END) as count_A'),
                DB::raw('COUNT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN 1 END) as count_B'),
                DB::raw('COUNT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN 1 END) as count_C'),
                DB::raw('COUNT(CASE WHEN m.mark < 40 THEN 1 END) as count_D')
            )
            ->when($year, function ($query, $year) {
                $query->where('m.academic_year_id', $year);
            })
            ->when($syllabus, function ($query, $syllabus) {
                $query->where('m.syllabus_id', $syllabus);
            })
            ->when($gradeLevel, function ($query, $gradeLevel) {
                $query->where('c.grade_level_id', $gradeLevel);
            })
            ->when($subject, function ($query, $subject) {
                $query->where('m.subject_id', $subject);
            })
            ->when($examType, function ($query, $examType) {
                $query->where('m.exam_type_id', $examType);
            })
            ->groupBy('m.academic_year_id', 'g.grade_name', 's.subject_name') // Group by academic year, grade name, and subject name
            ->get();

        // Fetch all filter options for the dropdowns
        $subjects = SubjectModel::all();
        $syllabuses = SyllabusModel::all();
        $gradeLevels = GradeLevelModel::all();
        $academicYears = AcademicYearModel::all();
        $examTypes = ExamTypeModel::all();

        // Return the view with the data and filter options
        return view('admin.analyticManagement.bySubject.subject', compact(
            'data', 'subjects', 'syllabuses', 'gradeLevels', 'academicYears', 'examTypes'
        ));
    }

    public function individualPerformance()
    {
        // Fetch individual student data and return the individual analytics view
        return view('admin.analyticManagement.byIndividual.individual');
    }

    public function gradePerformance()
    {
        // Fetch subject-level data and return the subject analytics view
        return view('admin.analyticManagement.byGrade.grade');
    }
}
