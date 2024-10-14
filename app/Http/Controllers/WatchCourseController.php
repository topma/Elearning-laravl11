<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Material;
use App\Models\Progress;

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

        // Get the authenticated student's ID
        $studentId = currentUserId(); // Ensure this function returns the current user's ID

        // Check if progress record exists for the student and course
        $progress = Progress::where('student_id', $studentId)->where('course_id', $course->id)->first();

        if ($progress) {
            // Progress record exists, get the last viewed material and last viewed time
            $lastViewedMaterial = $progress->last_viewed_material_id ? Material::find($progress->last_viewed_material_id) : null;
            $lastViewedAt = $progress->last_viewed_at;
        } else {
            // If no progress exists, initialize variables for the view
            $progress = null; // Set progress to null if it doesn't exist
            $lastViewedMaterial = null; // No material viewed yet
            $lastViewedAt = null; // No last viewed time
            // Create a new progress record
            Progress::create([
                'student_id' => $studentId,
                'course_id' => $course->id,
                'progress_percentage' => 0, // Set to 0% if no progress
                'completed' => 0, 
                'last_viewed_material_id' => $currentMaterial ? $currentMaterial->id : null,
                'last_viewed_at' => now(), 
            ]);
        }

        // Continue with the course view, passing all necessary variables
        return view('frontend.watchCourse', compact('course', 'lessons', 'courseNo', 'currentLesson', 'currentMaterial', 'progress', 'lastViewedMaterial', 'lastViewedAt'));
    }

}
