<?php
namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\TeacherAssignClasses;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';

        // Check the role of the authenticated user
        if (Auth::user()->role == 'admin') {
            // Fetch summary metrics for admin
            $data['totalStudents'] = StudentModel::count();
            $data['totalTeachers'] = User::where('role', 'teacher')->count();
            $data['totalClasses'] = ClassModel::count();
            return view('admin.dashboard', $data);
        } elseif (Auth::user()->role == 'teacher') {
            $yearId = session('academic_year_id'); // Use session year ID if no parameter provided
            // Get current academic year details
            $data['currentAcademicYear'] = AcademicYearModel::find($yearId);
            if (!$data['currentAcademicYear']) {
                $data['error'] = 'No academic year is currently selected.';
            }
            // Fetch additional data for teacher dashboard if necessary
            $teacherId = auth()->id(); // Get logged-in teacher ID
            // Get assigned subjects for the teacher
            $data['assignedSubjects'] = TeacherAssignClasses::with(['subject', 'syllabus', 'class'])
                ->where('user_id', $teacherId)
                ->where('academic_year_id', $yearId)
                ->get();
            // $data['yearId'] = $yearId;
            return view('teacher.dashboard', $data);
        }

        // Default fallback, should not occur unless roles are misconfigured
        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }
}
