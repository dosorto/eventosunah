<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SpeakerAccessCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $speakerName,
        public string $email,
        public string $password,
        public string $eventName,
        public string $conferenceName,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Credenciales de acceso como conferencista',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.speaker-access-credentials',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
