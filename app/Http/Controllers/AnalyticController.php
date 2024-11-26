<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\ExamTypeModel;
use App\Models\GradeLevelModel;
use App\Models\SubjectModel;
use App\Models\SyllabusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{

    public function subjectPerformance(Request $request)
    {
        // Fetch filter values from the request
        $year = $request->input('academic_year_id');
        $syllabus = $request->input('syllabus_id');
        $gradeLevel = $request->input('grade_level_id');
        $subject = $request->input('subject_id');
        $examType = $request->input('exam_type_id');

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
        return view('admin.analyticManagement.bySubject.subjectAnalytic', compact(
            'data', 'subjects', 'syllabuses', 'gradeLevels', 'academicYears', 'examTypes'
        ));
    }

    public function individualPerformance()
    {
        // Fetch individual student data and return the individual analytics view
        return view('admin.analyticManagement.byIndividual.individualAnalytic');
    }

    // public function classPerformance(Request $request)
    // {
    //     // Fetch filter values from the request
    //     $year = $request->input('academic_year_id');
    //     $class = $request->input('class_id'); // Directly use class_id
    //     $examType = $request->input('exam_type_id');
    //     $syllabus = $request->input('syllabus_id');

    //     // Query to get performance counts for each subject in the selected class
    //     $data = DB::table('marks as m')
    //         ->join('subject as s', 'm.subject_id', '=', 's.id') // Join subject for subject name
    //         ->join('academic_year as ay', 'm.academic_year_id', '=', 'ay.id') // Join academic year for year name
    //         ->select(
    //             'ay.academic_year_name',
    //             'm.class_id', // Use class_id directly
    //             's.subject_name',
    //             DB::raw('COUNT(CASE WHEN m.mark >= 80 THEN 1 END) as count_A'),
    //             DB::raw('COUNT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN 1 END) as count_B'),
    //             DB::raw('COUNT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN 1 END) as count_C'),
    //             DB::raw('COUNT(CASE WHEN m.mark < 40 THEN 1 END) as count_D')
    //         )
    //         ->when($year, fn($query) => $query->where('m.academic_year_id', $year))
    //         ->when($class, fn($query) => $query->where('m.class_id', $class)) // Filter by class_id from marks table
    //         ->when($examType, fn($query) => $query->where('m.exam_type_id', $examType))
    //         ->when($syllabus, fn($query) => $query->where('m.syllabus_id', $syllabus))
    //         ->groupBy('ay.academic_year_name', 'm.class_id', 's.subject_name') // Group by class_id directly
    //         ->get();

    //     // Fetch dropdown data
    //     $academicYears = AcademicYearModel::all();
    //     $classes = ClassModel::all(); // Add class options for the dropdown
    //     $examTypes = ExamTypeModel::all();
    //     $syllabuses = SyllabusModel::all();
    //     $gradeLevels = GradeLevelModel::all();

    //     // Return the view
    //     return view('admin.analyticManagement.byClass.classAnalytic', compact(
    //         'data', 'academicYears', 'classes', 'examTypes', 'syllabuses', 'gradeLevels'
    //     ));
    // }

    public function classPerformance(Request $request)
    {
        // Fetch filter values from the request
        $year = $request->input('academic_year_id');
        $class = $request->input('class_id'); // Directly use class_id
        $examType = $request->input('exam_type_id');
        $syllabus = $request->input('syllabus_id');

        // Query to get performance counts for each subject in the selected class
        $data = DB::table('marks as m')
            ->join('subject as s', 'm.subject_id', '=', 's.id') // Join subject for subject name
            ->join('academic_year as ay', 'm.academic_year_id', '=', 'ay.id') // Join academic year for year name
            ->join('class as c', 'm.class_id', '=', 'c.id') // Join class table for class name
            ->select(
                'ay.academic_year_name', // Fetch academic year name
                'c.name as class_name', // Fetch class name
                's.subject_name', // Fetch subject name
                DB::raw('COUNT(CASE WHEN m.mark >= 80 THEN 1 END) as count_A'),
                DB::raw('COUNT(CASE WHEN m.mark >= 60 AND m.mark < 80 THEN 1 END) as count_B'),
                DB::raw('COUNT(CASE WHEN m.mark >= 40 AND m.mark < 60 THEN 1 END) as count_C'),
                DB::raw('COUNT(CASE WHEN m.mark < 40 THEN 1 END) as count_D')
            )
            ->when($year, fn($query) => $query->where('m.academic_year_id', $year))
            ->when($class, fn($query) => $query->where('m.class_id', $class)) // Filter by class_id from marks table
            ->when($examType, fn($query) => $query->where('m.exam_type_id', $examType))
            ->when($syllabus, fn($query) => $query->where('m.syllabus_id', $syllabus))
            ->groupBy('ay.academic_year_name', 'c.name', 's.subject_name') // Group by class name
            ->get();

        // Fetch dropdown data
        $academicYears = AcademicYearModel::all();
        $classes = ClassModel::all(); // Add class options for the dropdown
        $examTypes = ExamTypeModel::all();
        $syllabuses = SyllabusModel::all();
        $gradeLevels = GradeLevelModel::all();

        // Return the view
        return view('admin.analyticManagement.byClass.classAnalytic', compact(
            'data', 'academicYears', 'classes', 'examTypes', 'syllabuses', 'gradeLevels'
        ));
    }

}
