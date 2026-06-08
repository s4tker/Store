<?php

// archivo que define como se mantiene la sesion del usuario
use Illuminate\Support\Str;

// define duracion cookie seguridad y guardado de sesion
return [

    // elige donde se guarda la sesion
    'driver' => env('SESSION_DRIVER', 'database'),

    // define cuantos minutos dura la sesion
    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    // cierra la sesion al cerrar el navegador si se activa
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    // cifra la sesion si se activa
    'encrypt' => env('SESSION_ENCRYPT', false),

    // indica la carpeta para sesiones en archivos
    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    // indica la tabla de sesiones
    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    // limpia sesiones antiguas poco a poco
    'lottery' => [2, 100],

    // define el nombre de la cookie
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug((string) env('APP_NAME', 'laravel')).'-session'
    ),

    // define donde se usa la cookie
    'path' => env('SESSION_PATH', '/'),

    'domain' => env('SESSION_DOMAIN'),

    // usa la cookie solo con https si se activa
    'secure' => env('SESSION_SECURE_COOKIE'),

    // evita que javascript lea la cookie
    'http_only' => env('SESSION_HTTP_ONLY', true),

    // protege la cookie entre sitios
    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

    'serialization' => 'json',

];
