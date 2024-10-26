<?php

namespace App\Http\Controllers\Backend\Courses;
use getID3;
use App\Models\Material;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\Course\Materials\AddNewRequest;
use App\Http\Requests\Backend\Course\Materials\UpdateRequest;
use App\Models\Lesson;
use Exception;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

    public function editorShow()
    {
        return view('backend.course.material.editor');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createMaterial($id)
    {
        $decryptedId = encryptor('decrypt', $id);   
        
        // Find the lesson by decrypted ID
        $lesson = Lesson::where('id', $decryptedId)->first();
        
        // Check if the lesson exists
        if (!$lesson) {
            // Return an error message or redirect back with an error
            return redirect()->back()->withErrors(['danger' => 'Lesson not found.']);
        }

        // Pass the lesson to the view
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
            $material->content_data = $request->contentData;
            $lesson = Lesson::where('id', $request->lessonId)->first();
            $courseId = $lesson->course_id;

            if ($request->hasFile('content')) {
                $file = $request->file('content');

                // Check if the file is a video
                $mimeType = $file->getMimeType();
                $extension = $file->extension();

                // Allowed video MIME types
                $allowedMimeTypes = [
                    'video/mp4',
                    'video/x-msvideo',
                    'video/x-flv',
                    'video/x-matroska',
                    'video/quicktime',
                    'video/x-ms-wmv',
                    'video/mpeg',
                    'video/webm',
                    'video/ogg',
                ];

                // Allowed video file extensions
                $allowedExtensions = ['mp4', 'avi', 'flv', 'mkv', 'mov', 'wmv', 'mpg', 'webm', 'ogv'];

                // Validate MIME type and extension
                if (in_array($mimeType, $allowedMimeTypes) && in_array($extension, $allowedExtensions)) {
                    // Move the file and save the content
                    $contentName = rand(111, 999) . time() . '.' . $extension;
                    $file->move(public_path('uploads/courses/contents'), $contentName);
                    $material->content = $contentName;

                    // Get video duration
                    $getID3 = new getID3;
                    $fileInfo = $getID3->analyze(public_path('uploads/courses/contents/' . $contentName));
                    if (isset($fileInfo['playtime_string'])) {
                        $material->file_duration = $fileInfo['playtime_string']; // e.g., "12:34"
                    }
                    if (isset($fileInfo['filesize'])) {
                        $material->file_size = $fileInfo['filesize']; // Size in bytes
                    }
                } else {
                    return redirect()->back()->withInput()->with('error', 'Please upload a valid video file.');
                }
            }

            if ($material->save()) {
                return redirect()->route('lesson.show', encryptor('encrypt', $courseId))->with('success', 'Data Saved');;
            } else {
                return redirect()->back()->withInput()->with('error', 'Please try again');
            }
        } catch (Exception $e) {
            //dd($e);
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
        $material = Material::findOrFail(encryptor('decrypt', $id));
        $lessonId = $material->lesson_id;
        //return response()->json($decryptedId);
        $lesson = Lesson::findOrFail($lessonId);
        
        return view('backend.course.material.edit', compact('lesson', 'material'));
    }

    /** 
     * Update the specified resource in storage.
     */      

     public function update(UpdateRequest $request, $id)
    {
        try {
            // Find the existing material
            $material = Material::findOrFail(encryptor('decrypt', $id));
            
            // Update the material attributes
            $material->title = $request->materialTitle;
            $material->lesson_id = $request->lessonId;
            $material->type = $request->materialType;
            $material->content_data = $request->contentData;

            // Get the course ID from the lesson
            $lesson = Lesson::where('id', $request->lessonId)->first();
            $courseId = $lesson->course_id;

            // Check if a new file is being uploaded
            if ($request->hasFile('content')) {
                // Remove the current video file if it exists
                if ($material->content) {
                    // Construct the video file path
                    $videoFilePath = public_path('uploads/courses/contents/') . $material->content; // Removed basename

                    // Check if the file exists and delete it
                    if (File::exists($videoFilePath)) {
                        File::delete($videoFilePath);
                    } else {
                        Log::warning('File not found for deletion: ' . $videoFilePath);
                    }                     
                }

                // Handle the new file upload
                $file = $request->file('content');
                $mimeType = $file->getMimeType();
                $extension = $file->extension();

                // Allowed video MIME types
                $allowedMimeTypes = [
                    'video/mp4',
                    'video/x-msvideo',
                    'video/x-flv',
                    'video/x-matroska',
                    'video/quicktime',
                    'video/x-ms-wmv',
                    'video/mpeg',
                    'video/webm',
                    'video/ogg',
                ];

                // Allowed video file extensions
                $allowedExtensions = ['mp4', 'avi', 'flv', 'mkv', 'mov', 'wmv', 'mpg', 'webm', 'ogv'];

                // Validate MIME type and extension
                if (in_array($mimeType, $allowedMimeTypes) && in_array($extension, $allowedExtensions)) {
                    // Move the new file
                    $contentName = rand(111, 999) . time() . '.' . $extension;
                    $file->move(public_path('uploads/courses/contents'), $contentName);
                    $material->content = $contentName;

                    // Get video duration and size using getID3
                    $getID3 = new getID3;
                    $fileInfo = $getID3->analyze(public_path('uploads/courses/contents/' . $contentName));
                    if (isset($fileInfo['playtime_string'])) {
                        $material->file_duration = $fileInfo['playtime_string']; // e.g., "12:34"
                    }
                    if (isset($fileInfo['filesize'])) {
                        $material->file_size = $fileInfo['filesize']; // Size in bytes
                    }
                } else {
                    return redirect()->back()->withInput()->with('error', 'Please upload a valid video file.');
                }
            }

            // Save the updated material
            if ($material->save()) {
                $this->notice::success('Data Saved');
                return redirect()->route('lesson.show', encryptor('encrypt', $courseId));
            } else {
                $this->notice::error('Please try again');
                return redirect()->back()->withInput();
            }
        } catch (Exception $e) {
            // Log the error instead of using dd()
            Log::error('Error updating material: ' . $e->getMessage());
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

        // Check if the material type is 'video'
        if ($data->type === 'video') {
            // Retrieve the video filename or ID from the content
            $videoFilePath = public_path('uploads/courses/contents/') . basename($data->content);

            // Check if the file exists and delete it
            if (File::exists($videoFilePath)) {
                File::delete($videoFilePath);
            }
        }

        // Delete the material record
        if ($data->delete()) {
            $this->notice::error('Data Deleted!');
            return redirect()->back();
        }

        // Optional: handle case where delete fails
        $this->notice::error('Data could not be deleted!');
        return redirect()->back();
    }

}
