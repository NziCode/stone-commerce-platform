<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Plain notification sent to the site owner (new reservation, contact message, order …). */
class AdminAlert extends Mailable
{
    public function __construct(public string $alertTitle, public string $alertBody)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->alertTitle);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-alert');
    }
}
