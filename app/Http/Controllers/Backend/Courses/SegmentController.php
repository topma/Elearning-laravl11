<?php

namespace App\Http\Controllers\Backend\Courses; 

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\Course\Segments\AddNewRequest;
use App\Http\Requests\Backend\Course\Segments\UpdateRequest;
use App\Models\CourseCategory;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Segments;
use App\Models\Material;
use App\Models\Coupon;
use Exception;
use File; 

class SegmentController extends Controller
{ 
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // //$course = Course::paginate(10);
        // //$courseId = encryptor('decrypt', $id);  
        // $userRoleId = auth()->user()->role_id;
        // $instructorId = auth()->user()->instructor_id;
        // if($userRoleId == 1) {
        //     $segment = Segment::withCount('lesson')->paginate(10);
        // }
        // else {
        //     $segment = Segment::where('course_id', $courseId)->withCount('lesson')->paginate(10);
        // }
        
        // return view('backend.course.segments.index', compact('course'));
    }

    public function indexForAdmin()
    {
        $course = Course::paginate(10);
        return view('backend.course.segments.indexForAdmin', compact('course'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseCategory = CourseCategory::get();
        $userRoleId = auth()->user()->role_id;
        $instructorId = auth()->user()->instructor_id;
        if($userRoleId == 1){
            $instructor = Instructor::get();
        }
        else{
            $instructor = Instructor::where('id', $instructorId)->get();
        }
        
        return view('backend.course.courses.create', compact('courseCategory', 'instructor'));
    }

    public function createSegment($id)
    {
        $decryptedId = encryptor('decrypt', $id);   
        
        // Find the lesson by decrypted ID
        $course = Course::where('id', $decryptedId)->first();
        
        // Check if the lesson exists
        if (!$course) {
            // Return an error message or redirect back with an error
            return redirect()->back()->withErrors(['danger' => 'Course not found.']);
        }

        $courseCategory = CourseCategory::get();        
        $userRoleId = auth()->user()->role_id;
        $instructorId = auth()->user()->instructor_id;
        if($userRoleId == 1){
            $instructor = Instructor::get();
        }
        else{
            $instructor = Instructor::where('id', $instructorId)->first();
        }
        
        return view('backend.course.segments.create', compact('courseCategory', 'instructor','course'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewRequest $request)
    {
        try {
            $segment = new Segments;
            $segment->title_en = $request->title_en;
            $segment->description_en = $request->description_en;
            $segment->course_category_id = $request->categoryId;
            $segment->instructor_id = $request->instructorId;
            $segment->course_id = $request->courseId;
            $segment->lesson = $request->lesson;
            $segment->segment_no = $request->segmentNo;
            $segment->status = 2;

            if ($request->hasFile('image')) {
                $imageName = rand(111, 999) . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
                $segment->image = $imageName;
            }
            if ($request->hasFile('thumbnail_image')) {
                $thumbnailImageName = rand(111, 999) . time() . '.' . $request->thumbnail_image->extension();
                $request->thumbnail_image->move(public_path('uploads/courses/thumbnails'), $thumbnailImageName);
                $segment->thumbnail_image = $thumbnailImageName;
            }
            if ($segment->save())
                return redirect()->route('segment.show', encryptor('encrypt', $request->courseId))->with('success', 'Data Saved');
            else
                return redirect()->back()->withInput()->with('error', 'Please try again');
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
        // 
        $courseId = encryptor('decrypt', $id);  
        //return response()->json($courseId);
        $userRoleId = auth()->user()->role_id;
        $instructorId = auth()->user()->instructor_id;
        if($userRoleId == 1) {
            $segment = Segments::withCount('lesson')->paginate(10);           
           
        }        
        else {
            $segment = Segments::where('course_id', encryptor('decrypt', $id))
            ->withCount('lesson')
            ->orderBy('segment_no', 'asc')
            ->paginate(10);
            $segmentNew = Segments::where('course_id', encryptor('decrypt', $id))->first();
            $courseId = $segmentNew->course_id;
            $course = Course::where('id', $courseId)->first();            
        }
        
        return view('backend.course.segments.index', compact('segment','courseId','course','segmentNew'));
        
    }

    public function frontShow($id)
    {
        $course = Course::findOrFail(encryptor('decrypt', $id));
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
        
        return view('frontend.courseDetails', compact('course','lesson','courseNo','coupon','relatedCourse'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $courseCategory = CourseCategory::get();
        $instructor = Instructor::get();
        $segment = Segments::findOrFail(encryptor('decrypt', $id));
        return view('backend.course.segments.edit', compact('courseCategory', 'instructor', 'segment'));
    }

    /** 
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            $segment = Segments::findOrFail(encryptor('decrypt', $id));
            $courseId = $segment->course_id;
            if ($request->has('start_from') && !empty($request->start_from)) {
                $course->start_from = $request->start_from; // Update if the date is chosen
            }
            $segment->title_en = $request->title_en;
            $segment->description_en = $request->description_en;
            $segment->segment_no = $request->segmentNo;

            if ($request->hasFile('image')) {
                $imageName = rand(111, 999) . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
                $segment->image = $imageName;
            }
            if ($request->hasFile('thumbnail_image')) {
                $thumbnailImageName = rand(111, 999) . time() . '.' . $request->thumbnail_image->extension();
                $request->thumbnail_image->move(public_path('uploads/courses/thumbnails'), $thumbnailImageName);
                $segment->thumbnail_image = $thumbnailImageName;
            }
            if ($segment->save())
                return redirect()->route('segment.show', encryptor('encrypt', $courseId))->with('success', 'Data Saved');
            else
                return redirect()->back()->withInput()->with('error', 'Please try again');
        } catch (Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('error', 'Please try again');
        }
    }

    public function updateforAdmin(UpdateRequest $request, $id)
    {
        try {
            $course = Course::findOrFail(encryptor('decrypt', $id));
            if ($request->has('start_from') && !empty($request->start_from)) {
                $course->start_from = $request->start_from; // Update if the date is chosen
            }
            $course->title_en = $request->courseTitle_en;
            $course->title_bn = $request->courseTitle_bn;
            $course->description_en = $request->courseDescription_en;
            $course->description_bn = $request->courseDescription_bn;
            $course->course_category_id = $request->categoryId;
            $course->instructor_id = $request->instructorId;
            $course->type = $request->courseType;
            $course->currency_type = $request->currencyType;
            $course->price = $request->coursePrice;
            $course->old_price = $request->courseOldPrice; 
            $course->subscription_price = $request->subscription_price;
            $course->duration = $request->duration;
            $course->segments = $request->segment;
            $course->difficulty = $request->courseDifficulty;
            $course->course_code = $request->course_code;
            $course->prerequisites_en = $request->prerequisites_en;
            $course->prerequisites_bn = $request->prerequisites_bn;
            $course->thumbnail_video = $request->thumbnail_video;
            $course->tag = $request->tag;
            $course->status = $request->status;
            $course->language = 'en';

            if ($request->hasFile('image')) {
                $imageName = rand(111, 999) . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
                $course->image = $imageName;
            }
            if ($request->hasFile('thumbnail_image')) {
                $thumbnailImageName = rand(111, 999) . time() . '.' . $request->thumbnail_image->extension();
                $request->thumbnail_image->move(public_path('uploads/courses/thumbnails'), $thumbnailImageName);
                $course->thumbnail_image = $thumbnailImageName;
            }
            if ($course->save())
                return redirect()->route('courseList')->with('success', 'Data Saved');
            else
                return redirect()->back()->withInput()->with('error', 'Please try again');
        } catch (Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('error', 'Please try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Segments::findOrFail(encryptor('decrypt', $id));
        $image_path = public_path('uploads/courses') . $data->image;

        if ($data->delete()) {
            if (File::exists($image_path))
                File::delete($image_path);

            return redirect()->back();
        }
    }
}
