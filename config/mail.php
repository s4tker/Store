<?php

// archivo que define como se envian correos
// define smtp servicios externos y remitente
return [

    // elige como se envian correos
    'default' => env('MAIL_MAILER', 'log'),

    // lista servicios para enviar correos
    'mailers' => [

        // envia correos con smtp
        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        // envia correos con amazon
        'ses' => [
            'transport' => 'ses',
        ],

        // envia correos con postmark
        'postmark' => [
            'transport' => 'postmark',
        ],

        // envia correos con resend
        'resend' => [
            'transport' => 'resend',
        ],

        // envia correos desde el servidor
        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        // guarda correos en logs para pruebas
        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        // guarda correos en memoria
        'array' => [
            'transport' => 'array',
        ],

        // usa otro servicio si uno falla
        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
            'retry_after' => 60,
        ],

        // reparte correos entre servicios
        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
            'retry_after' => 60,
        ],

    ],

    // define quien aparece como remitente
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

];
