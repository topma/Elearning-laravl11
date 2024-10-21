<?php

namespace App\Http\Controllers\Backend\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Http\Requests\Backend\Instructors\AddNewRequest;
use App\Http\Requests\Backend\Instructors\UpdateRequest;
use App\Models\Role;
use App\Models\Lesson;
use Illuminate\Support\Facades\Hash;
use Exception;
use File;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role_id = auth()->user()->role_id;
        $user_id = auth()->user()->instructor_id;

        // If role is Superadmin, show all instructors
        if ($role_id == 1) {
            $instructor = Instructor::paginate(10);
        } 
        // If role is Instructor, show all except the logged-in instructor
        elseif ($role_id == 3) {
            $instructor = Instructor::where('id', '!=', $user_id)->get();
        }
        
        return view('backend.instructor.index', compact('instructor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = Role::get();
        return view('backend.instructor.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddNewRequest $request)
    {
        try {
            DB::beginTransaction();
            $instructor = new Instructor;
            $instructor->name_en = $request->fullName_en;
            //$instructor->name_bn = $request->fullName_bn;
            $instructor->contact_en = $request->contactNumber_en;
            //$instructor->contact_bn = $request->contactNumber_bn;
            $instructor->email = $request->emailAddress;
            $instructor->role_id = $request->roleId;
            $instructor->bio = $request->bio;
            $instructor->designation = $request->designation;
            $instructor->social_facebook = $request->social_facebook;
            $instructor->social_twitter = $request->social_twitter;
            $instructor->social_instagram = $request->social_instagram;
            $instructor->social_linkedin = $request->social_linkedin;
            $instructor->social_youtube = $request->social_youtube;
            $instructor->title = $request->title;
            $instructor->status = $request->status;
            $instructor->password = Hash::make($request->password);
            $instructorUrl = Str::random(40);
            $instructor->instructor_url = $instructorUrl;
            $instructor->language = 'en';
            $instructor->access_block = $request->access_block;
            if ($request->hasFile('image')) {
                $imageName = (Role::find($request->roleId)->name) . '_' .  $request->fullName_en . '_' . rand(999, 111) .  '.' . $request->image->extension();
                $request->image->move(public_path('uploads/users'), $imageName);
                $instructor->image = $imageName;
            }

            if ($instructor->save()) {
                $user = new User;
                $user->instructor_id = $instructor->id;
                $user->name_en = $request->fullName_en;
                $user->email = $request->emailAddress;
                $user->contact_en = $request->contactNumber_en;
                $user->role_id = $request->roleId;
                $user->status = $request->status;
                $user->password = Hash::make($request->password);
                if (isset($imageName)) {
                    $user->image = $imageName; // Save the image name in the users table
                }
                if ($user->save()) {
                    DB::commit();
                    $this->notice::success('Successfully saved');
                    return redirect()->route('instructor.index');
                }
            } else
                return redirect()->back()->withInput()->with('error', 'Please try again');
        } catch (Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('error', 'Please try again');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructor $instructor)
    {
        //
    }

    public function frontShow($id)
    {
        $instructor = Instructor::findOrFail(encryptor('decrypt', $id));
        // dd($course); 
        // Get the number of courses the instructor has
        $courseCount = $instructor->courses()->count();

        // Get the total number of students enrolled in all the instructor's courses
        $enrollmentCount = Enrollment::whereIn('course_id', $instructor->courses()->pluck('id'))->count();

        //-----Get the instructor's courses ----- 
        $instructorCourse = Course::where('instructor_id', encryptor('decrypt', $id)) 
        ->where('status', 2) 
        ->withCount('lessons') 
        ->paginate(10); 

        return view('frontend.instructorProfile', compact('instructor', 'courseCount', 'enrollmentCount'
        ,'instructorCourse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::get();
        $instructor = Instructor::findOrFail(encryptor('decrypt', $id));
        return view('backend.instructor.edit', compact('role', 'instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            // Fetch the instructor using the decrypted ID
            $instructor = Instructor::findOrFail(encryptor('decrypt', $id));
    
            // Update instructor fields
            $instructor->name_en = $request->fullName_en;
            $instructor->name_bn = $request->fullName_bn;
            $instructor->contact_en = $request->contactNumber_en;
            $instructor->contact_bn = $request->contactNumber_bn;
            $instructor->email = $request->emailAddress;
            $instructor->role_id = $request->roleId;
            $instructor->bio = $request->bio;
            $instructor->designation = $request->designation;
            $instructor->social_facebook = $request->social_facebook;
            $instructor->social_twitter = $request->social_twitter;
            $instructor->social_instagram = $request->social_instagram;
            $instructor->social_linkedin = $request->social_linkedin;
            $instructor->social_youtube = $request->social_youtube;
            $instructor->title = $request->title;
            $instructor->status = $request->status;
            $instructor->language = 'en';
            $instructor->access_block = $request->access_block;
    
            // Check and hash password only if provided
            if (!empty($request->password)) {
                $instructor->password = Hash::make($request->password);
            }
    
            // Generate instructor URL if not present
            if (empty($instructor->instructor_url)) {
                $instructor->instructor_url = Str::random(40);
            }
    
            // Handle image upload
            if ($request->hasFile('image')) {
                $imageName = Role::find($request->roleId)->name . '_' . $request->fullName_en . '_' . rand(999, 111) . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/users'), $imageName);
                $instructor->image = $imageName;
            }
    
            // Save instructor
            if ($instructor->save()) {
                // Update related user information
                $user = User::where('instructor_id', $instructor->id)->first();
                $user->instructor_id = $instructor->id;
                $user->name_en = $request->fullName_en;
                $user->email = $request->emailAddress;
                $user->contact_en = $request->contactNumber_en;
                $user->role_id = $request->roleId;
                $user->status = $request->status;
    
                // Hash password only if provided
                if (!empty($request->password)) {
                    $user->password = Hash::make($request->password);
                }
    
                // If image was uploaded, save it in the user model as well
                if (isset($imageName)) {
                    $user->image = $imageName;
                }
    
                // Save user
                if ($user->save()) {
                    DB::commit(); // Commit the transaction
    
                    // Success notice
                    $this->notice::success('Successfully saved');
                    return redirect()->route('instructor.index');
                }
            }
    
            // If saving fails
            DB::rollBack(); // Rollback on failure
            return redirect()->back()->withInput()->with('error', 'Please try again');
    
        } catch (Exception $e) {
            DB::rollBack(); // Rollback on error
            Log::error('Update error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Please try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Instructor::findOrFail(encryptor('decrypt', $id));
        $image_path = public_path('uploads/instructors') . $data->image;

        if ($data->delete()) {
            if (File::exists($image_path))
                File::delete($image_path);

            return redirect()->back();
        }
    }
}
