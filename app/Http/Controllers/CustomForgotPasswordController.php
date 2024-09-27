<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class CustomForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        if (Auth::check()) {
            // User is already logged in, handle it accordingly
            return redirect()->route('dashboard'); // Redirect to the dashboard or another page
        }

        $request->validate([
            'email' => 'required|email'
        ]);
        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with(['success' => __($status)]);
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('studentLogin')->with('success', __($status));
        }
        return back()->withErrors(['email' => [__($status)]]);
    }

    public function userForgotPassword(Request $request)
    {
        if (Auth::check()) {
            // User is already logged in, handle it accordingly
            return redirect()->route('studentdashboard'); // Redirect to the dashboard or another page
        }

        $request->validate([
            'email' => 'required|email|exists:students,email', // Check if the email exists in students table
        ]);

        // Use the custom password broker for students
        $status = Password::broker('students')->sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with(['success' => __($status)]);
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function userResetPassword(Request $request)
    {
        // Step 1: Validate the request data
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:students,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Step 2: Check the students table for the email
        $student = \App\Models\Student::where('email', $request->email)->first();
        if (!$student) {
            Log::error('User not found', ['email' => $request->email]);
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Step 3: Retrieve the hashed token from the password_resets table
        $resetData = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$resetData) {
            Log::error('No password reset data found', ['email' => $request->email]);
            return back()->withErrors(['email' => 'No password reset request found.']);
        }

        // Step 4: Check if the token from the request matches the hashed token
        if (!Hash::check($request->token, $resetData->token)) {
            Log::error('Token does not match', ['expected' => $resetData->token, 'received' => $request->token]);
            return back()->withErrors(['token' => 'The provided token is invalid.']);
        }

        // Step 5: Update the password
        $student->forceFill([
            'password' => bcrypt($request->password),
        ])->save();

        // Optionally, delete the password reset entry
        DB::table('password_resets')->where('email', $request->email)->delete();

        Log::info('Password updated successfully', ['email' => $request->email]);
        return redirect()->route('studentLogin')->with('success', 'Password has been updated successfully.');
    }

}
