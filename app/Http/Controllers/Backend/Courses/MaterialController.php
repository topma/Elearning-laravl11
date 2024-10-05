<?php

namespace App\Http\Controllers\Backend\Courses;

use App\Models\Material;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\Course\Materials\AddNewRequest;
use App\Http\Requests\Backend\Course\Materials\UpdateRequest;
use App\Models\Lesson;
use Exception;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $material = Material::paginate(10);
        return view('backend.course.material.index', compact('material'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $lessonId = $request->query('lesson_id');
        $decryptedId = encryptor('decrypt', $lessonId);
        // Check if the ID is valid
        if (!$decryptedId) {
            // Handle the error (redirect, return error message, etc.)
            return redirect()->back()->withErrors(['error' => 'Invalid lesson ID.']);
        }
        
        $lesson = Lesson::findOrFail($decryptedId);
        return view('backend.course.material.create', compact('lesson'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewRequest $request)
    {
        try {
            $material = new Material;
            $material->title = $request->materialTitle;
            $material->lesson_id = $request->lessonId;
            $material->type = $request->materialType;
            $material->content = $request->content;
            $material->content_url = $request->contentURL;

            if ($request->hasFile('content')) {
                $contentName = rand(111, 999) . time() . '.' . $request->content->extension();
                $request->content->move(public_path('uploads/courses/contents'), $contentName);
                $material->content = $contentName;
            }
            if ($material->save()) {
                return redirect()->route('material.show', encryptor('encrypt', $request->lessonId))->with('success', 'Data Saved');;
            } else {
                return redirect()->back()->withInput()->with('error', 'Please try again');
            }
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Please try again');
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
        $lesson = Lesson::findOrFail($decryptedId);

        // Get lessons associated with the course
        $material = Material::where('lesson_id', $lesson->id)->get();

        // Return the view with the course and its lessons
        return view('backend.course.material.view', compact('lesson', 'material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $decryptedId = encryptor('decrypt', $id);
        $lesson = Lesson::findOrFail($decryptedId);
        $material = Material::findOrFail(encryptor('decrypt', $id));
        return view('backend.course.material.edit', compact('lesson', 'material'));
    }

    /** 
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $material = Material::findOrFail(encryptor('decrypt', $id));
            $material->title = $request->materialTitle;
            $material->lesson_id = $request->lessonId;
            $material->type = $request->materialType;
            $material->content_url = $request->contentURL;

            if ($request->hasFile('content')) {
                $contentName = rand(111, 999) . time() . '.' . $request->content->extension();
                $request->content->move(public_path('uploads/courses/contents'), $contentName);
                $material->content = $contentName;
            }
            if ($material->save()) {
                $this->notice::success('Data Saved');
                return redirect()->route('material.show', encryptor('encrypt', $request->lessonId));
            } else {
                $this->notice::error('Please try again');
                return redirect()->back()->withInput();
            }
        } catch (Exception $e) {
            dd($e);
            $this->notice::error('Please try again');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Material::findOrFail(encryptor('decrypt', $id));
        if ($data->delete()) {
            $this->notice::error('Data Deleted!');
            return redirect()->back();
        }
    }
}
