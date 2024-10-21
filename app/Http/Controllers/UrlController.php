<?php

namespace App\Http\Controllers;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Student;
use App\Models\Lesson;
use App\Models\Coupon;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    //

    public function instructorUrl($instructor_url)
    {       
        
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
