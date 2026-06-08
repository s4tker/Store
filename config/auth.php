<?php

// archivo que define como entran los usuarios
use App\Models\User;

// define inicio de sesion usuarios y recuperacion de clave
return [

    // define el acceso normal del usuario
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    // guarda la sesion al iniciar sesion
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    // indica de donde salen los usuarios
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    // prepara la recuperacion de clave
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    // define cuanto dura confirmar la clave
    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
