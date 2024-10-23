<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Material;
use App\Models\Progress;
use App\Models\ProgressAll;
use App\Models\Segments;
use App\Models\Enrollment;
use App\Models\Quiz;

class WatchCourseController extends Controller
{
    public function watchCourse($id)
    {
        // Get the authenticated student's ID
        $studentId = currentUserId();     

        //Get segment no
        $segment = Segments::where('id' , encryptor('decrypt',$id))->first();
        $segmentNo = $segment->segment_no;
        $courseId = $segment->course_id;
        if ($segmentNo == 1){
            $previousSegment = $segmentNo;
        }
        else{
            $previousSegment = $segmentNo - 1;
        }

        $course = Course::findOrFail($courseId);
        $instructorId = $course->instructor_id;
        $lessons = Lesson::where('segments_id', $segment->id)->with('material')->get();
        $lessonsFirst = Lesson::where('segments_id', $segment->id)->first();
        $materialFirst = Material::where('lesson_id', $lessonsFirst->id)->first();
        $courseNo = Course::where('instructor_id', $instructorId)->get();   

        //Get student segmentno for the course
        $stdSegment = Enrollment::where('student_id', $studentId)
        ->where('course_id', $courseId)
        ->first();
        $stdSegmentNo = $stdSegment->segment;
        
        //Check if student has completed a segment before proceeding to the next one
        if ($segmentNo > $stdSegmentNo ) {
            return redirect()->back()->with('error', 'You have not completed segment ' . $previousSegment);
        }
        // Check if progress record exists for the student and course
        $progress = Progress::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('segments_id', $segment->id)
            ->first();

        if ($progress) {
            // Progress record exists, get the last viewed material and last viewed time
            $lastViewedMaterial = $progress->last_viewed_material_id ? Material::find($progress->last_viewed_material_id) : null;
            $lastViewedAt = $progress->last_viewed_at;
            $lastviewedLesson = $progress->last_viewed_lesson_id;  
            $currentLesson = Lesson::where('segments_id', $segment->id)
            ->where('id', $lastviewedLesson)
            ->first();
            $currentMaterial = Material::where('lesson_id', $currentLesson->id)->first();          
        } else {
            // If no progress exists, initialize variables for the view
            $lastViewedMaterial = null; 
            $lastViewedAt = null; 
            $lastviewedLesson = null ;

            //Create a new ProgressAll record
            ProgressAll::Create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'progress_percentage' => 0, 
                'completed' => 1, 
                'material_id' => $materialFirst->id, 
                'lesson_id' => $lessonsFirst->id, 
                'last_viewed_at' => now(), 
                'segments_id' => $segment->id,
                'segment_no' => $segmentNo,
            ]);
            
            // Create a new progress record
            Progress::create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'progress_percentage' => 0, 
                'completed' => 0, 
                'last_viewed_material_id' => $materialFirst->id, 
                'last_viewed_lesson_id' => $lessonsFirst->id, 
                'last_viewed_at' => now(), 
                'segments_id' => $segment->id,
                'segment_no' => $segmentNo,
                'quiz_attempts' => 0,
            ]);           

            $currentLesson = Lesson::where('segments_id', $segment->id)->first();
            $currentMaterial = Material::where('lesson_id', $lessonsFirst->id)->first();          
            
        }

        // Retrieve all progress records for this student and course
        $progressRecords = ProgressAll::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('segments_id', $segment->id)
            ->pluck('material_id') // Get the material IDs that have been clicked
            ->toArray();

        $segments = Segments::withCount('lesson')
            ->where('id', $segment->id)
            ->get();
        
            // Initialize an array to store progress percentages for each segment
        $segmentProgress = [];

        // Iterate through segments to calculate progress
        foreach ($segments as $segment) {
            // Get total lessons for the current segment
            $totalLessons = $segment->lesson_count; // Using withCount from Eloquent

            // Fetch completed lessons from ProgressAll model where the student and segment match
            $completedLessons = ProgressAll::where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->where('segments_id', $segment->id) // Ensure you're filtering by segment
                ->where('completed', 1) // Assuming 1 means completed
                ->count(); // Count the number of completed lessons

            // Avoid division by zero and calculate percentage
            $percentage = ($totalLessons > 0) ? ($completedLessons / $totalLessons) * 100 : 0;

            // Store the percentage rounded to 2 decimal places
            $segmentProgress[$segment->id] = round($percentage, 2);
        //    dd($totalLessons,$completedLessons,$segmentProgress);
        }

        //---get quiz for segment of a particular course if available
        $quiz= Quiz::where('course_id', $courseId)
        ->where('segment_id', $segment->id)
        ->first();
            
        // Continue with the course view, passing all necessary variables
        return view('frontend.watchCourse', compact(
            'course', 
            'lessons', 
            'courseNo', 
            'lastViewedMaterial', 
            'lastViewedAt', 
            'progressRecords','currentLesson','currentMaterial','progress','segment',
            'segmentProgress','studentId','courseId','quiz'
        ));
    }


}
