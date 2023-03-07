<?php

namespace App\Mail;

use App\Models\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendWelcomeToAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Admin $admin, public $password)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[OMS] Welcome To OMS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.send-welcome-mail-to-admin',
            with: [
                $this->admin,
                $this->password,
            ]
        );
    }
}
