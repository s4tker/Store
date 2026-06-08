<?php

// archivo que prepara un correo del sistema
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// esta clase arma el correo de codigo
class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;
    // recibe los datos iniciales

    public function __construct(
        public readonly string $code,
        public readonly string $email,
        public readonly int $minutes = 10,
    ) {
    }
    // define el asunto del correo

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu codigo de verificacion',
        );
    }
    // arma el contenido del correo

    public function content(): Content
    {
        return new Content(
            view: 'emails.Auth.otp-verification',
        );
    }
}
