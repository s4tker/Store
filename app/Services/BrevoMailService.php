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
        $apiKey = $this->apiKey();

        if ($apiKey !== '') {
            $this->sendWithBrevoApi($apiKey, $to, $mailable);
            return;
        }

        Mail::to($to)->send($mailable);
    }

    private function apiKey(): string
    {
        $apiKey = (string) config('services.brevo.key', '');

        if ($apiKey !== '') {
            return $apiKey;
        }

        $mailPassword = (string) config('mail.mailers.smtp.password', '');

        return str_starts_with($mailPassword, 'xkeysib-') ? $mailPassword : '';
    }

    private function sendWithBrevoApi(string $apiKey, string $to, Mailable $mailable): void
    {
        $html = $mailable->render();
        $envelope = $mailable->envelope();
        $verifySsl = config('services.brevo.verify_ssl');

        $response = Http::withOptions([
            'verify' => $verifySsl === null ? ! app()->environment('local') : (bool) $verifySsl,
        ])->withHeaders([
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
