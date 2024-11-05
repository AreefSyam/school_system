<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboard(){

        $data['header_title'] = 'Dashboard';
        if(Auth::user()->role == 'admin'){
            return view('admin.dashboard', $data);
        }
        else if(Auth::user()->role == 'teacher'){
            return view('teacher.dashboard', $data);
        }
    }
}
