<?php

namespace App\Http\Controllers;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\Coupon;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    //

    public function instructorUrl($instructor_url)
    {
        // Find the instructor by URL or fail
        $instructor = Instructor::where('instructor_url', $instructor_url)->firstOrFail();
        
        // Get the number of courses the instructor has
        $courseCount = $instructor->courses()->count();

        // Get the total number of students enrolled in all the instructor's courses
        $enrollmentCount = Enrollment::whereIn('course_id', $instructor->courses()->pluck('id'))->count();

        // Get the instructor's courses with lesson count
        $instructorCourse = Course::where('instructor_id', $instructor->id)
            ->where('status', 2)
            ->withCount('lessons')
            ->paginate(10);

        return view('frontend.instructorProfile', compact('instructor', 'courseCount', 'enrollmentCount', 'instructorCourse'));
    }

   

    public function courseUrl($course_url)
    {
        $student = Student::find(currentUserId());
        //check if student exists
        if(!$student){
            $id = Course::where('course_url', $course_url)->first();
            $course = Course::findOrFail($id->id);
        $courseId = $course->id;
        $courseCategoryId = $course->course_category_id;
        $instructorId = $course->instructor_id;
        
        $lesson = Lesson::where('course_id', $courseId)->get(); 
        $courseNo = Course::where('instructor_id', $instructorId)->get();
        $coupon = Coupon::where('course_id', $courseId)->first();
        
        // Exclude the current course from related courses
        $relatedCourse = Course::where('course_category_id', $courseCategoryId)
            ->where('id', '!=', $courseId) // Exclude the current course
            ->get();
        
        return view('frontend.courseDetails', compact('course','lesson',
        'courseNo','coupon','relatedCourse'));
        } 
        else{
            $id = Course::where('course_url', $course_url)->first();
            $course = Course::findOrFail($id->id);
            $courseId = $course->id;
            $courseCategoryId = $course->course_category_id;
            $instructorId = $course->instructor_id;
            $enrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $courseId);
            if($enrollment){
                return redirect()->route('courseSegment', ['id' => encryptor('encrypt', $courseId)]);
            }
        }
    }
}
