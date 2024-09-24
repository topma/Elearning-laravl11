<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::get();
        $student = Student::all();
        
        if (fullAccess())
            return view('backend.adminDashboard', compact('student')); 
        else
        if ($user->role = 'instructor')
            return view('backend.instructorDashboard', compact('student')); 
        else
            return view('backend.dashboard', compact('student'));

        //   $user = User::get();
        //   if($user->role = 'instructor') 
        //     return view('backend.instructorDashboard');
    }
}
