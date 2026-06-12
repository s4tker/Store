<?php

// archivo que define claves de servicios externos
// define correo nube y notificaciones
return [

    // guarda la clave de postmark
    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    // guarda la clave de resend
    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    // guarda la clave API de Brevo para correos transaccionales
    'brevo' => [
        'key' => env('BREVO_API_KEY'),
    ],

    // guarda datos de amazon
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // guarda datos para avisos por slack
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
