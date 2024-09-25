<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MyCustomEmail;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MailController extends Controller
{
    //

    public function index()
    {
        try {
            // Retrieve email address from the session
            $email_address = session('email');
            $full_name = session('full_name');
            $email_token = session('email_token');
                      

            $data['email'] = $email_address;
            $data['full_name'] = "Dear user," ;
            $data['email_token'] = $email_token;            
            $data['title'] = 'Email Verification';

            // Load the PDF
            $pdf = PDF::loadview('backend.emails.sendmail', $data);

            $data['pdf'] = $pdf;

            // Send the email
            Mail::to($data['email'])->send(new MyCustomEmail($data));

            // Success: Email sent
            //session()->flash('success', 'Account setup successful! You can login to complete your profile.');

            return redirect()->route('email-verify');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error during mail: ' . $e->getMessage()); 
            // Error handling: Handle the error and display an error message
            session()->flash('error', 'An error occurred while sending the email.');

            return redirect()->route('studentLogin');
        }
    }

    public function emailVerify()
    {
        $email_address = session('email');
        $email_message = session('email_message');  
        return view('students.auth.email-verify')
                ->with('email', $email_address)
                ->with('message', $email_message);
    }

    public function emailNotVerify(Request $request)
    {
        $validatedData = $request->validate([            
            'email' => 'required|email',            
        ]);

        $email_address = $validatedData['email'];  
        session(['email' => $email_address]);
        
        return redirect()->route('resend-verification-email');     
    }

    public function resendEmailVerification()
    {
        $email = session('email');

        try {
            // Find the user by email
            $user = Student::where('email', $email)->first();
    
            if ($user) {
                // Generate a new verification token
                $newToken = Str::random(40);
                session(['email_token' => $newToken]);
                // Update the user's verification token in the database
                $user->remember_token = $newToken;
                $user->save();  
                
                $email_message = "A new verification email has been sent to you, kindly follow instructions to continue, 
                please check both your inbox and spam folder.";
                session(['email_message' => $email_message]);
                // Flash a success message and redirect
                //Session::flash('success', 'A new verification email has been sent. Please check your email for the link.');
                return redirect()->route('send-mail');
            } else {
                // Flash an error message for invalid email
                Session::flash('error', 'Email not found. Please check your email address and try again.');
                return redirect()->route('studentLogin');
            }
        } catch (Exception $e) {
            // Log the error
            Log::error('Error during email verification resend: ' . $e->getMessage());    
            
            return redirect()->route('email-verify');
        }
    }

    public function resendVerification(Request $request)
    {
        $email = $request->input('email');
        session(['email' => $email]);

        try {
            // Find the user by email
            $user = Student::where('email', $email)->first();
    
            if ($user) {
                // Generate a new verification token
                $newToken = Str::random(40);
                session(['email_token' => $newToken]);
                // Update the user's verification token in the database
                $user->remember_token = $newToken;
                $user->save();  
                
                $email_message = "A new verification email has been sent to you, kindly follow instructions to continue, 
                please check both your inbox and spam folder.";
                session(['email_message' => $email_message]);
                // Flash a success message and redirect
                //Session::flash('success', 'A new verification email has been sent. Please check your email for the link.');
                return redirect()->route('send-mail');
            } else {
                // Flash an error message for invalid email
                Session::flash('error', 'Email not found. Please check your email address and try again.');
                return redirect()->route('studentLogin');
            }
        } catch (Exception $e) {
            // Log the error
            Log::error('Error during email verification resend: ' . $e->getMessage());    
            
            return redirect()->route('email-verify');
        }
    }

    public function emailVerifyDone($token)
    {
        try {
            // Find the user with the given token
            $user = Student::where('remember_token', $token)->first();

            if ($user) {
                // Mark the email as verified
                $user->email_verified_at = now();
                $user->email_verified_status = 1;
                $user->save();

                // Flash a success message and redirect
                Session::flash('success', 'Email has been verified. You can now login to complete your profile.');
                return redirect()->route('studentLogin');
            } else {
                // Flash an error message for invalid token
                Session::flash('error', 'Invalid verification token. Please click on the button below to resend the verification link.');
                return redirect()->route('email-verify');
            }
        } catch (Exception $e) {
            // Handle the exception, log it, and redirect as needed
            // For example, you can log the error and redirect to an error page
            Log::error('Error during email verification: ' . $e->getMessage());
            return redirect()->route('email-verify');
        }
    }
}
