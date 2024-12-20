<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ExamModel;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\AcademicYearModel;
use App\Models\TeacherAssignClasses;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';

        // Check the role of the authenticated user
        if (Auth::user()->role === 'admin') {
            // Admin metrics
            $data['totalStudents'] = StudentModel::count();
            $data['totalTeachers'] = User::where('role', 'teacher')->count();
            $data['totalClasses'] = ClassModel::count();
            return view('admin.dashboard', $data);
        } elseif (Auth::user()->role === 'teacher') {
            $yearId = session('academic_year_id'); // Use session-stored academic year ID

            if (!$yearId) {
                // Fetch current academic year if not set
                $currentYear = AcademicYearModel::orderBy('start_date', 'desc')->first();

                if ($currentYear) {
                    $yearId = $currentYear->id;
                    session(['academic_year_id' => $yearId]);
                } else {
                    $data['error'] = 'No academic year is currently active.';
                    return view('teacher.dashboard', $data);
                }
            }

            // Fetch current academic year details
            $data['currentAcademicYear'] = AcademicYearModel::find($yearId);

            if (!$data['currentAcademicYear']) {
                $data['error'] = 'No academic year is currently selected.';
                return view('teacher.dashboard', $data);
            }

            // Fetch assigned subjects for the teacher
            $teacherId = auth()->id();
            $assignedSubjects = TeacherAssignClasses::with(['subject', 'syllabus', 'class'])
                ->where('user_id', $teacherId)
                ->where('academic_year_id', $yearId)
                ->get();

            $data['assignedSubjects'] = $assignedSubjects;

            // Fetch examinations for the current academic year and associated syllabi
            $syllabusIds = $assignedSubjects->pluck('syllabus_id')->unique();
            $data['examinations'] = ExamModel::where('academic_year_id', $yearId)
                ->whereIn('syllabus_id', $syllabusIds)
                ->get();

            return view('teacher.dashboard', $data);
        }

        // Default fallback
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}
