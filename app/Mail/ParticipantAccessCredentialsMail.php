<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParticipantAccessCredentialsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $participantName,
        public string $email,
        public string $password,
        public string $eventName,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Acceso a tu cuenta en EventIS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.participant-access-credentials',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
