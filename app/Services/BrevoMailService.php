<?php

// servicio liviano para enviar correos transaccionales por Brevo API
namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class BrevoMailService
{
    public function send(string $to, Mailable $mailable): void
    {
        $apiKey = (string) config('services.brevo.key', '');

        if ($apiKey === '') {
            Mail::to($to)->send($mailable);
            return;
        }

        $html = $mailable->render();
        $envelope = $mailable->envelope();

        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => config('mail.from.name'),
                'email' => config('mail.from.address'),
            ],
            'to' => [
                ['email' => $to],
            ],
            'subject' => $envelope->subject,
            'htmlContent' => $html,
        ]);

        $response->throw();
    }
}
