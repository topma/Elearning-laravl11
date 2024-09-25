<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Models\Instructor;
use App\Http\Requests\Students\Auth\SignUpRequest;
use App\Http\Requests\Students\Auth\SignInRequest;
use Illuminate\Support\Facades\Hash;
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

    public function instructorSignUpStore(SignUpRequest $request,$back_route)
    {
        try {
            $instructor = new Instructor;
            $instructor->name_en = $request->name;
            $instructor->email = $request->email;
            $instructor->password = Hash::make($request->password);            
            $instructor->nationality = "n/a";
            $instructor->image = "blank.jpg";
            if ($instructor->save()){
                $this->setSession($student);
                //------save to user table with the instructor id
                $user = new User;
                $user->instructor_id = $instructor->id;
                $user->name_en = $request->name;
                $user->email = $request->email;
                $user->role_id = 3;
                $user->status = 'active';
                $user->password = Hash::make($request->password);
                $user->save();
                //--------------------------------
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

    public function signInForm()
    {
        return view('students.auth.login');
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

    public function signOut()
    {
        request()->session()->flush();
        return redirect()->route('studentLogin')->with('danger', 'Succesfully Logged Out');
    }
}
