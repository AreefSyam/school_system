<?php
namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\StudentModel;
use App\Models\TeacherAssignClasses;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboard()
    {
        // Setting the header title for all dashboards
        $data['header_title'] = 'Dashboard';

        // Check the role of the authenticated user to determine the appropriate dashboard
        if (Auth::user()->role === 'admin') {

            // Calculate metrics specifically for admin views
            $data['totalStudents'] = StudentModel::count();
            $data['totalTeachers'] = User::where('role', 'teacher')->count();
            $data['totalClasses']  = ClassModel::count();
            // Render the admin dashboard with metrics
            return view('admin.dashboard', $data);

        } elseif (Auth::user()->role === 'teacher') {
                                                   // Ensure there is a current academic year set; fetch or error out as needed
            $yearId = session('academic_year_id'); // Retrieve academic year ID from session

            if (! $yearId) {
                // Fetch current academic year if not set
                $currentYear = AcademicYearModel::orderBy('start_date', 'desc')->first();
                if ($currentYear) {
                    $yearId = $currentYear->id;
                    session(['academic_year_id' => $yearId]);
                } else {
                    $data['error'] = 'No academic year is currently active.';
                    return view('teacher.dashboard', $data); // No active year, show error on dashboard
                }
            }

            // Fetch current academic year details
            $data['currentAcademicYear'] = AcademicYearModel::find($yearId);
            if (! $data['currentAcademicYear']) {
                $data['error'] = 'No academic year is currently selected.';
                return view('teacher.dashboard', $data);
            }

            // Fetch assigned subjects for the teacher
            $teacherId        = auth()->id();
            $assignedSubjects = TeacherAssignClasses::with(['subject', 'syllabus', 'class'])
                ->where('user_id', $teacherId)
                ->where('academic_year_id', $yearId)
                ->get();
            $data['assignedSubjects'] = $assignedSubjects;

            // Fetch examinations for the current academic year and associated syllabi
            $syllabusIds          = $assignedSubjects->pluck('syllabus_id')->unique();
            $data['examinations'] = ExamModel::where('academic_year_id', $yearId)
                ->whereIn('syllabus_id', $syllabusIds)
                ->get();

            return view('teacher.dashboard', $data); // Render the teacher dashboard
        }

        // Fallback for any other role or condition
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}
