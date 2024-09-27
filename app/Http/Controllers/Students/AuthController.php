<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Models\Instructor;
use App\Http\Requests\Students\Auth\SignUpRequest;
use App\Http\Requests\Students\Auth\SignUpRequestInstructor;
use App\Http\Requests\Students\Auth\SignInRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signUpForm()
    {
        return view('students.auth.register');
    }

    public function instructorSignUpForm()
    {
        return view('students.auth.register-instructor');
    }

    public function signUpStore(SignUpRequest $request,$back_route)
    {
        try {
            $student = new Student;
            $student->name_en = $request->name;
            $student->email = $request->email;
            $student->password = Hash::make($request->password);
            $student->email_verified_status = 0;
            $student->nationality = "n/a";
            $student->image = "blank.jpg";
            if ($student->save()){
                $this->setSession($student);
                $email_token =Str::random(40);
                $email_message = "We have sent instructions to verify your email, kindly follow instructions to continue, 
                please check both your inbox and spam folder.";
                session(['email' => $request->email]);
                session(['full_name' => $request->name]);
                session(['email_token' => $email_token]);
                session(['email_message' => $email_message]);               
                
                return redirect()->route('send-mail');
                // return redirect()->route($back_route)->with('success', 'Successfully Logged In');
            }
        } catch (Exception $e) {
            //dd($e);
            return redirect()->back()->with('danger', 'Please Try Again');
        }
    }

    public function instructorSignUpStore(SignUpRequestInstructor $request, $back_route)
    {
        try {
            // Create and save instructor data
            $instructor = new Instructor;
            $instructor->name_en = $request->name;
            $instructor->email = $request->email;
            $instructor->password = Hash::make($request->password);            
            $instructor->contact_en = $request->contact;
            $instructor->image = 'blank.jpg';
            $instructor->role_id = 3;
            $instructor->status = 1;
            $instructor->language = 'en';
            
            if ($instructor->save()) {
                // Set session with the instructor details
                $this->setSessionInstructor($instructor); 

                // Create and save user data with instructor ID
                $user = new User;
                $user->instructor_id = $instructor->id; // Set instructor_id
                $user->name_en = $request->name;
                $user->email = $request->email;
                $user->contact_en = $request->contact;
                $user->role_id = 3; // Assuming 3 is the role for instructor
                $user->status = 1;
                $user->full_access = 0;
                $user->language = 'en';
                $user->password = Hash::make($request->password);
                $user->save();

                // Set email verification session data
                $email_token = Str::random(40);
                $email_message = "We have sent instructions to verify your email. Kindly check your inbox and spam folder.";
                session(['email' => $request->email]);
                session(['full_name' => $request->name]);
                session(['email_token' => $email_token]);
                session(['email_message' => $email_message]);

                return redirect()->route('send-mail');
            } else {
                return redirect()->back()->with('danger', 'Instructor save failed');
            }
        } catch (Exception $e) {
            // Log exception for debugging
            Log::error('Error occurred while saving instructor and user:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('danger', 'Please Try Again');
        }
    }


    public function signInForm()
    {
        return view('frontend.signin');
    }

    public function instructorSignInForm()
    {
        return view('students.auth.login-instructor');
    }


    public function signInCheck(SignInRequest $request,$back_route)
    {
        try {
            $student = Student::Where('email', $request->email)->first();
            if ($student) {
                if ($student->status == 1) {
                    if (Hash::check($request->password, $student->password)) {
                        $this->setSession($student);
                        if ($student->email_verified_status == 1) {
                            // Email is verified, proceed with login 
                            $request->session()->regenerate(); 
                            // $intendedUrl = session('url.intended', '/');
                            // return redirect()->intended($intendedUrl);
                            return redirect()->route($back_route)->with('success', 'Successfully Logged In');
                        } else {                    
                            // Email is not verified, return a flash message
                            //Auth::logout(); // Log the user out since the email is not verified                    
                            $email_address = $request->email;         
                             return view('auth.email-not-verify');                             
                        }
                        
                    } else
                        return redirect()->back()->with('error', 'Username or Password is wrong!');
                } else
                    return redirect()->back()->with('error', 'You are not an active user! Please contact to Authority');
            } else
                return redirect()->back()->with('error', 'Username or Password is wrong!');
        } catch (Exception $e) {
            //dd($e);
            return redirect()->back()->with('error', 'Username or Password is wrong!');
        }
    }

    public function instructorSignInCheck(SignInRequest $request,$back_route)
    {
        try {
            $student = Student::Where('email', $request->email)->first();
            if ($student) {
                if ($student->status == 1) {
                    if (Hash::check($request->password, $student->password)) {
                        $this->setSession($student);
                        if ($student->email_verified_status == 1) {
                            // Email is verified, proceed with login 
                            $request->session()->regenerate(); 
                            // $intendedUrl = session('url.intended', '/');
                            // return redirect()->intended($intendedUrl);
                            return redirect()->route($back_route)->with('success', 'Successfully Logged In');
                        } else {                    
                            // Email is not verified, return a flash message
                            //Auth::logout(); // Log the user out since the email is not verified                    
                            $email_address = $request->email;         
                             return view('auth.email-not-verify');                             
                        }
                        
                    } else
                        return redirect()->back()->with('error', 'Username or Password is wrong!');
                } else
                    return redirect()->back()->with('error', 'You are not an active user! Please contact to Authority');
            } else
                return redirect()->back()->with('error', 'Username or Password is wrong!');
        } catch (Exception $e) {
            //dd($e);
            return redirect()->back()->with('error', 'Username or Password is wrong!');
        }
    }

    public function setSession($student)
    {
        return request()->session()->put(
            [
                'userId' => encryptor('encrypt', $student->id),
                'userName' => encryptor('encrypt', $student->name_en),
                'emailAddress' => encryptor('encrypt', $student->email),
                'studentLogin' => 1,
                'image' => $student->image ?? 'No Image Found' 
            ]
        );
    }

    public function setSessionInstructor($instructor)
    {
        return request()->session()->put(
            [
                'userId' => encryptor('encrypt', $instructor->id),
                'userName' => encryptor('encrypt', $instructor->name_en),
                'emailAddress' => encryptor('encrypt', $instructor->email),
                'studentLogin' => 1,
                'image' => $instructor->image ?? 'No Image Found' 
            ]
        );
    }

    public function signOut()
    {
        request()->session()->flush();
        return redirect()->route('studentLogin')->with('danger', 'Succesfully Logged Out');
    }
}
