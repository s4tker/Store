<?php

// archivo que define datos generales de la tienda
// define nombre modo idioma y seguridad
return [

    // define el nombre que se muestra en la tienda
    'name' => env('APP_NAME', 'Laravel'),

    // define si esta en prueba o produccion
    'env' => env('APP_ENV', 'production'),

    // muestra errores completos al desarrollar
    'debug' => (bool) env('APP_DEBUG', false),

    // define la direccion del sitio
    'url' => env('APP_URL', 'http://localhost'),

    // define la hora del sistema
    'timezone' => env('APP_TIMEZONE', 'America/Lima'),

    // define el idioma principal
    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    // protege datos internos
    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    // activa el modo mantenimiento
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
