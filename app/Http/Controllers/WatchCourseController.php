<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Material;

class WatchCourseController extends Controller
{


    public function watchCourse($id)
    {
        $course = Course::findOrFail(encryptor('decrypt', $id));
        $instructorId = $course->instructor_id;
        $lessons = Lesson::where('course_id', $course->id)->get();
        $courseNo = Course::where('instructor_id', $instructorId)->get();
        $currentLesson = Lesson::where('course_id', $course->id)->first();
        $currentMaterial = Material::where('lesson_id', $currentLesson->id)->first();

        return view('frontend.watchCourse', compact('course', 'lessons','courseNo',
        'currentLesson','currentMaterial'));
    }


}
