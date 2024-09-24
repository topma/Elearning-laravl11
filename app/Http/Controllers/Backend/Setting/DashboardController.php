<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::get();
        $student = Student::all();
        $course = Course::all();
        
        if (fullAccess())
            return view('backend.adminDashboard', compact('student','course')); 
        else
        if ($user->role = 'instructor')
            return view('backend.instructorDashboard', compact('student','course')); 
        else
            return view('backend.dashboard', compact('student','course'));

        //   $user = User::get();
        //   if($user->role = 'instructor') 
        //     return view('backend.instructorDashboard');
    }
}
