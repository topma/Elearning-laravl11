<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class CustomForgotPasswordController extends Controller
{
    //
    public function forgotPassword(Request $request)
    {
        if (Auth::check()) {
            // User is already logged in, handle it accordingly
            return redirect()->route('studentdashboard'); // Redirect to the dashboard or another page
        }
        
        $request->validate([
            'email' => 'required|email'
        ]);
        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with(['success' => __($status)]);
            //session()->flash('success', 'Reset password link has been sent to the registered email address.');
        }
        
        return back()->withErrors(['email' => __($status)]);
        // return response()->json([
        //     'status' => 'success',
        // ]);
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
}
