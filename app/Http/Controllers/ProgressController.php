<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\ProgressAll;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function updateProgress(Request $request)
    {
        \Log::info('Request Data: ', $request->all());
        
        $studentId = currentUserId(); // Replace with your method to get the current user ID
        $courseId = $request->input('courseid');
        $materialId = $request->input('materialid');
        $lessonId = $request->input('lessonid');
        $segmentId = $request->input('segmentid');
        $segmentNo = $request->input('segmentno');

        // Check if a record exists in progress_alls with the given criteria
        $progressAllExists = ProgressAll::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('lesson_id', $lessonId)
            ->where('material_id', $materialId)
            ->exists();

        // If the record does not exist, create a new one
        if (!$progressAllExists) {
            ProgressAll::create([
                'student_id' => $studentId,
                'course_id' => $courseId,
                'lesson_id' => $lessonId,
                'material_id' => $materialId,
                'segments_id' => $segmentId,
                'segment_no' => $segmentNo,
                'last_viewed_at' => now(), 
                'progress_percentage' => 0,
                'completed' => true,
            ]);
        }

        // Check if the progress record exists for the student and course
        $progress = Progress::where('student_id', $studentId)
                            ->where('course_id', $courseId)
                            ->where('segments_id', $segmentId)
                            ->first();

        // If the record exists, update the last viewed material and lesson
        if ($progress) {
            $progress->last_viewed_material_id = $materialId;
            $progress->last_viewed_lesson_id = $lessonId;
            $progress->save();

            return response()->json(['success' => true, 'message' => 'Progress updated successfully.']);
        }

        // If the record does not exist, return a failure response
        return response()->json(['success' => false, 'message' => 'Progress not found.']);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Progress $progress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Progress $progress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Progress $progress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Progress $progress)
    {
        //
    }
}
