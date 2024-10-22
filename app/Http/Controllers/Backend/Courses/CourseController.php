<?php

namespace App\Http\Controllers\Backend\Courses; 

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\Course\Courses\AddNewRequest;
use App\Http\Requests\Backend\Course\Courses\UpdateRequest;
use App\Models\CourseCategory;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Segments;
use App\Models\Material;
use App\Models\Coupon;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Str;
use Exception;
use File; 
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{ 
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$course = Course::paginate(10);
        $userRoleId = auth()->user()->role_id;
        $instructorId = auth()->user()->instructor_id;
        if($userRoleId == 1) {
            $course = Course::withCount('segment')->paginate(10);
        }
        else {
            $course = Course::where('instructor_id', $instructorId)->withCount('segment')->paginate(10);
        }
        
        return view('backend.course.courses.index', compact('course'));
    }

    public function indexForAdmin()
    {
        $course = Course::paginate(10);
        return view('backend.course.courses.indexForAdmin', compact('course'));
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewRequest $request)
    {
        try {
            $course = new Course;
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
            $course->subscription_price = $request->subscriptionPrice;
            $course->start_from = $request->start_from;
            $course->duration = $request->duration;
            $course->segment = $request->segment;
            $course->difficulty = $request->courseDifficulty;
            $course->course_code = $request->course_code;
            $course->prerequisites_en = $request->prerequisites_en;
            $course->prerequisites_bn = $request->prerequisites_bn;
            $course->thumbnail_video = $request->thumbnail_video;
            $course->tag = $request->tag; 
            $course->date_enabled = $request->dateEnabled;
            $courseUrl = Str::random(40);
            $course->course_url = $courseUrl;
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
                return redirect()->route('course.index')->with('success', 'Data Saved');
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
        
    }

    public function frontShow($id)
    {
        $student = Student::find(currentUserId());
        //check if student exists
        if(!$student)   {
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
        
        return view('frontend.courseDetails', compact('course','lesson',
        'courseNo','coupon','relatedCourse'));
        } 
        else{
            $course = Course::findOrFail(encryptor('decrypt', $id));
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



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $instructorId = auth()->user()->instructor_id;
        $courseCategory = CourseCategory::get();
        $instructor = Instructor::where('id', $instructorId)->get();
        $course = Course::findOrFail(encryptor('decrypt', $id));
        return view('backend.course.courses.edit', compact('courseCategory', 'instructor', 'course'));
    }

    /** 
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
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
            $course->segment = $request->segment;
            $course->difficulty = $request->courseDifficulty;
            $course->course_code = $request->course_code;
            $course->prerequisites_en = $request->prerequisites_en;
            $course->prerequisites_bn = $request->prerequisites_bn;
            $course->thumbnail_video = $request->thumbnail_video;
            $course->tag = $request->tag;
            $course->date_enabled = $request->dateEnabled;
            $course->language = 'en';

            // Generate course URL if not present
            if (empty($course->course_url)) {
                $course->course_url = Str::random(40);
            }

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
                return redirect()->route('course.index')->with('success', 'Data Saved');
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
            $course->segment = $request->segment;
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

    public function getSegments($courseId)
    {
        try {
            // Log the incoming request
            Log::info('Fetching segments for course', ['course_id' => $courseId]);

            // Fetch segments
            $segments = Segments::where('course_id', $courseId)->get(['id', 'title_en']);

            // Log the retrieved segments
            // if ($segments->isEmpty()) {
            //     Log::info('No segments found for course', ['course_id' => $courseId]);
            // } else {
            //     Log::info('Segments found', ['course_id' => $courseId, 'segments' => $segments]);
            // }
            \Log::info('Returning segments as JSON', ['segments' => $segments]);

            return response()->json($segments);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching segments for course', ['course_id' => $courseId, 'error' => $e->getMessage()]);
            
            // Return a JSON error response
            return response()->json(['error' => 'Failed to retrieve segments'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Course::findOrFail(encryptor('decrypt', $id));
        $image_path = public_path('uploads/courses') . $data->image;

        if ($data->delete()) {
            if (File::exists($image_path))
                File::delete($image_path);

            return redirect()->back();
        }
    }
}
