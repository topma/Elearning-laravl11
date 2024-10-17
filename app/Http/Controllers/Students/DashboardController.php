<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Segments;
use App\Models\Checkout;
use App\Models\Material;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student_info = Student::find(currentUserId());
        
        // Fetch enrollment with course, lesson count, and progress
        $enrollment = Enrollment::with([
            'course.segments', 
            'course.lessons',
            'course.instructor.courses' 
        ])
        ->where('student_id', currentUserId())
        ->paginate(10);

        $course = Course::get();
        $checkout = Checkout::where('student_id', currentUserId())->get();

        return view('students.dashboard', compact('student_info', 'enrollment', 'course', 'checkout'));
    }

    public function courseSegment($id)
    {
        // Fetch the current student information
        $student_info = Student::find(currentUserId());

       // In your controller method
       $enrollment = Enrollment::with(['course.segments', 'course.lessons'])
       ->where('student_id', currentUserId())
        ->paginate(10);

        // Fetch all courses
        $course = Course::all();

        // Fetch checkout data for the current student
        $checkout = Checkout::where('student_id', currentUserId())->get();

        // Decrypt the course ID
        $decryptedCourseId = encryptor('decrypt', $id);  
        
        $segments = Segments::withCount('lesson')
        ->where('course_id', $decryptedCourseId)
        ->paginate(10);

        // Return the data to the view
        return view('students.course-segment', compact('enrollment', 'checkout', 'course', 'student_info',
    'segments'));
    }



}
