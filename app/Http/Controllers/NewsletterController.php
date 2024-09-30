<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Newsletter; 
use App\Mail\NewsletterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{   
    public function subscribe(Request $request)
    {
        try {
            // Validate the email input
            $request->validate([
                'email' => 'required|email',
            ]);

            // Check if the email already exists in the database
            $newsletter = Newsletter::where('email', $request->email)->first();

            if (!$newsletter) {
                // Email not found, so save the email to the database
                $newsletter = new Newsletter;
                $newsletter->email = $request->email;
                $newsletter->save();

                // Generate token 
                $email_token = Str::random(32); 

                // Send confirmation email
                Mail::to($request->email)->send(new NewsletterMail($email_token));

                // Redirect with success message
                return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
            }

            // If email already exists, return a message without saving again
            return redirect()->back()->with('error', 'You have already subscribed to our newsletter.');
        
        } catch (\Exception $e) {
            // Log any errors that occur
            Log::error('Subscription error: ' . $e->getMessage(), ['exception' => $e]);

            // Redirect back with error message
            return redirect()->back()->with('error', 'An error occurred during the subscription process. Please try again later.');
        }
    }


    
}

