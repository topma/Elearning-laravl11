<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Checkout;
use App\Models\Material;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student_info = Student::find(currentUserId());
        
        // Fetch enrollment with course, lesson count, and progress
        $enrollment = Enrollment::where('student_id', currentUserId())
            ->with(['course' => function ($query) {
                $query->withCount('lesson')
                    ->with(['progress' => function ($query) {
                        $query->where('student_id', currentUserId()); // Filter progress by the current student
                    }]);
            }])
            ->paginate(10);

        $course = Course::get();
        $checkout = Checkout::where('student_id', currentUserId())->get();

        return view('students.dashboard', compact('student_info', 'enrollment', 'course', 'checkout'));
    }

}
