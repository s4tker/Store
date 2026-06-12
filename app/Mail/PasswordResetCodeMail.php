<?php

// archivo que prepara el correo para recuperar contraseña
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// esta clase arma el correo de recuperacion de contraseña
class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $code,
        public readonly string $email,
        public readonly int $minutes = 10,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Codigo para restablecer tu contraseña',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.Auth.password-reset-code',
        );
    }
}
