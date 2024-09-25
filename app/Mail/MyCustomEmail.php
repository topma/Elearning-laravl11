<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class MyCustomEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $maildata;

    /**
     * Create a new message instance.
     */
     
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }
    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->maildata['title'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'backend.emails.sendmail',
            with: $this->maildata
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    //-----Disable attachments temporarily--------
    // public function attachments(): array
    // {
    //     return [
    //         Attachment::fromData(fn() =>$this->maildata['pdf']->output(),'report.pdf')
    //             ->withMime('application/pdf'),

    //     ];
    // }
    //----end
}
