<?php

namespace App\Mail;

use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendChangePasswordMailToUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public $password)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[OMS] Password Updated',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.send-change-password-mail-to-user',
            with: [
                $this->user,
                $this->password
            ]
        );
    }
}
