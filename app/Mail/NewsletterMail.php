<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{   
    use Queueable, SerializesModels;

    public $email_token;

    public function __construct($email_token)
    {
        $this->email_token = $email_token;
    }

    public function build()
    {
        return $this->subject('Newsletter Subscription Confirmation')
                    ->view('emails.newslettermail')  // Blade template
                    ->with([
                        'email_token' => $this->email_token,
                    ]);
    }
}
