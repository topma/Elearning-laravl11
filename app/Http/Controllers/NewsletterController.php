<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter; 
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email|unique:news_letters,email',
        ]);

        // Check if the email already exists in the database
        $newsletter = Newsletter::where('email', $request->email)->first();

        if (!$newsletter) {
            // Email not found, so save the email to the database
            $newsletter = new Newsletter;
            $newsletter->email = $request->email;
            $newsletter->save();

            // Generate token (if needed)
            $email_token = str_random(32); // Adjust token generation

            // Send confirmation email
            Mail::to($request->email)->send(new NewsletterMail($email_token));

            // Redirect with success message
            return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
        }

        // If email already exists, return a message without saving again
        return redirect()->back()->with('error', 'You have already subscribed to our newsletter.');
    }


    
}

