<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\Material;
use App\Models\Segments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Course\Lessons\AddNewRequest;
use App\Http\Requests\Backend\Course\Lessons\UpdateRequest;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index()
    {
        $lesson = Lesson::paginate(10);
        return view('backend.course.lesson.index', compact('lesson'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)    
    {    
        $segmentId = $request->query('segment_id');
        $decryptedId = encryptor('decrypt', $segmentId);
        
        // Check if the ID is valid
        if (!$decryptedId) {
            // Handle the error (redirect, return error message, etc.)
            return redirect()->back()->withErrors(['error' => 'Invalid course ID.']);
        }
        
        $segment = Segments::findOrFail($decryptedId);        
        return view('backend.course.lesson.create', compact('segment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add($id)
    {
        $course = Course::findOrFail(encryptor('decrypt', $id));
        return view('backend.course.lesson.add', compact('course'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewRequest $request)
    {
        try {
            $lesson = new Lesson;
            $lesson->title = $request->lessonTitle;
            $lesson->serial_no = $request->serialNo;
            $lesson->course_id = $request->courseId;
            $lesson->segments_id = $request->segmentId;
            $lesson->description = $request->lessonDescription;
            $lesson->notes = $request->lessonNotes;

            if ($lesson->save()) {
                $this->notice::success('Data Saved');
                return redirect()->route('lesson.show', encryptor('encrypt', $request->segmentId));
            } else {
                $this->notice::error('Please try again');
                return redirect()->back()->withInput();
            }
        } catch (Exception $e) {
            // dd($e);
            $this->notice::error('Please try again');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {        
        // Decrypt the ID
        $decryptedId = encryptor('decrypt', $id);

        // Find the course
        $segment = Segments::findOrFail($decryptedId);

        // Get lessons associated with the course
        $lesson = Lesson::where('segments_id', $segment->id)
        ->withCount('material') 
        ->orderBy('serial_no', 'asc')
        ->get();

        // Return the view with the course and its lessons
        return view('backend.course.lesson.view', compact('segment', 'lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $course = Course::get();
        $lesson = Lesson::findOrFail(encryptor('decrypt', $id));
        return view('backend.course.lesson.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $lesson = Lesson::findOrFail(encryptor('decrypt', $id));
            $lesson->title = $request->lessonTitle;
            $lesson->serial_no = $request->serialNo;
            $lesson->course_id = $request->courseId;
            // $lesson->segments_id = $request->segmentId;
            $lesson->description = $request->lessonDescription;
            $lesson->notes = $request->lessonNotes;

            if ($lesson->save()) {
                $this->notice::success('Data Saved');
                return redirect()->route('lesson.show', encryptor('encrypt', $request->courseId));
            } else {
                $this->notice::error('Please try again');
                return redirect()->back()->withInput();
            }
        } catch (Exception $e) {
            // dd($e);
            $this->notice::error('Please try again');
            return redirect()->back()->withInput();
        }
    }

    public function loadLesson(Request $request)
    {
        $studentId = currentUserId();
        $course = Course::findOrFail(encryptor('decrypt', $id));
        $instructorId = $course->instructor_id;
        $lessons = Lesson::where('course_id', $course->id)->get();
        $courseNo = Course::where('instructor_id', $instructorId)->get();   

        // Check if progress record exists for the student and course
        $progress = Progress::where('student_id', $studentId)
        ->where('course_id', $course->id)->first();

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

        //$currentLesson = Lesson::where('course_id', $course->id)->first();
        $currentMaterial = Material::where('lesson_id', $currentLesson->id)->first();
        // Get the lesson ID from the request
        $lessonId = $request->input('lesson_id');
        
        // Find the current lesson
        $currentMaterial = Material::findOrFail($lessonId);

        // Fetch the previous lesson
        $previousLesson = Material::where('id', '<', $lessonId)
            ->orderBy('id', 'desc') // Get the highest ID less than the current one
            ->first();

        // Fetch the next lesson
        $nextLesson = Material::where('id', '>', $lessonId)
            ->orderBy('id', 'asc') // Get the lowest ID greater than the current one
            ->first();

        // Return the view with the current material and the previous/next lessons
        return view('frontend.watchCourse', compact('currentMaterial', 'previousLesson', 'nextLesson'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Lesson::findOrFail(encryptor('decrypt', $id));
        if ($data->delete()) {
            $this->notice::error('Data Deleted!');
            return redirect()->back();
        }
    }
}
