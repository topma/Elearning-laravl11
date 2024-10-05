<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Http\Controllers\Controller;
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
        $courseId = $request->query('course_id');
        $decryptedId = encryptor('decrypt', $courseId);
        
        // Check if the ID is valid
        if (!$decryptedId) {
            // Handle the error (redirect, return error message, etc.)
            return redirect()->back()->withErrors(['error' => 'Invalid course ID.']);
        }
        
        $course = Course::findOrFail($decryptedId);
        return view('backend.course.lesson.create', compact('course'));
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
    public function store(Request $request)
    {
        try {
            $lesson = new Lesson;
            $lesson->title = $request->lessonTitle;
            $lesson->course_id = $request->courseId;
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {        
        // Decrypt the ID
        $decryptedId = encryptor('decrypt', $id);

        // Find the course
        $course = Course::findOrFail($decryptedId);

        // Get lessons associated with the course
        $lesson = Lesson::where('course_id', $course->id)->get();

        // Return the view with the course and its lessons
        return view('backend.course.lesson.view', compact('course', 'lesson'));
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
    public function update(Request $request, $id)
    {
        try {
            $lesson = Lesson::findOrFail(encryptor('decrypt', $id));
            $lesson->title = $request->lessonTitle;
            $lesson->course_id = $request->courseId;
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
