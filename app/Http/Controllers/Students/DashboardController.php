<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Segments;
use App\Models\Checkout;
use App\Models\Material;
use App\Models\Progress;
use App\Models\ProgressAll;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $studentId = currentUserId();
        $student_info = Student::find($studentId);
        // Fetch enrollment with course, lesson count, and progress
        $enrollment = Enrollment::with([
            'course.segments', 
            'course.lessons',
            'course.instructor.courses' 
        ])
        ->where('student_id', currentUserId())
        ->paginate(10);

        // Fetch progress data for each course
        $progress = Progress::where('student_id', $studentId)->get()->keyBy('course_id'); 

        $course = Course::get();
        $checkout = Checkout::where('student_id', currentUserId())->get();

        // Calculate progress percentage for each enrolled course
        $courseProgress = [];

        foreach ($enrollment as $enroll) {
            $totalLessons = $enroll->course->lessons->count(); // Total lessons in the course
            
            // Fetch the number of completed lessons from ProgressAll model where the student and course match
            $completedLessons = ProgressAll::where('student_id', currentUserId())
                                ->where('course_id', $enroll->course_id)
                                ->where('completed', 1) // Assuming 1 means completed
                                ->count(); // Count the number of completed lessons
            
            // Avoid division by zero and calculate percentage
            $percentage = ($totalLessons > 0) ? ($completedLessons / $totalLessons) * 100 : 0;
            
            // Store the percentage rounded to 2 decimal places
            $courseProgress[$enroll->course_id] = round($percentage, 2);            
        }  

        return view('students.dashboard', compact('student_info', 'enrollment', 'course', 
        'checkout','progress','courseProgress'));
    }

    public function courseSegment($id)
    {
        // Fetch the current student information
        $studentId = currentUserId();
        $student_info = Student::find($studentId);

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

        // Fetch segments along with lesson counts
        $segments = Segments::withCount('lesson')
            ->where('course_id', $decryptedCourseId)
            ->orderBy('segment_no', 'asc')
            ->paginate(10);

        $progress = Progress::where('student_id', $studentId)
            ->where('course_id', $decryptedCourseId)
            ->get() 
            ->keyBy('segments_id');

        // Initialize an array to store progress percentages for each segment
        $segmentProgress = [];

        // Iterate through segments to calculate progress
        foreach ($segments as $segment) {
            // Get total lessons for the current segment
            $totalLessons = $segment->lesson_count; // Using withCount from Eloquent

            // Fetch completed lessons from ProgressAll model where the student and segment match
            $completedLessons = ProgressAll::where('student_id', $studentId)
                ->where('course_id', $decryptedCourseId)
                ->where('segments_id', $segment->id) // Ensure you're filtering by segment
                ->where('completed', 1) // Assuming 1 means completed
                ->count(); // Count the number of completed lessons

            // Avoid division by zero and calculate percentage
            $percentage = ($totalLessons > 0) ? ($completedLessons / $totalLessons) * 100 : 0;

            // Store the percentage rounded to 2 decimal places
            $segmentProgress[$segment->id] = round($percentage, 2);
        }  
        // dd($segmentProgress);
        // Return the data to the view
        return view('students.course-segment', compact(
            'enrollment', 
            'checkout', 
            'course', 
            'student_info',
            'segments',
            'segmentProgress',
            'progress', // Pass the segment progress to the view
        ));
    }

}
