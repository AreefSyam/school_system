<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class DashboardController extends Controller
// {

//     public function dashboard(){

//         $data['header_title'] = 'Dashboard';
//         if(Auth::user()->role == 'admin'){
//             return view('admin.dashboard', $data);
//         }
//         else if(Auth::user()->role == 'teacher'){
//             return view('teacher.dashboard', $data);
//         }
//     }
// }


namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentModel;
use App\Models\ClassModel;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['header_title'] = 'Dashboard';

        // Check the role of the authenticated user
        if (Auth::user()->role == 'admin') {
            // Fetch summary metrics for admin
            $data['totalStudents'] = StudentModel::count();
            $data['totalTeachers'] = User::count();
            $data['totalClasses'] = ClassModel::count();
            return view('admin.dashboard', $data);
        } elseif (Auth::user()->role == 'teacher') {
            // Teacher-specific dashboard
            return view('teacher.dashboard', $data);
        }
    }
}
