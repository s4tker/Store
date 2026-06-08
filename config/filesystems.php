<?php

// archivo que define donde se guardan archivos e imagenes
// define disco local publico y nube
return [

    // elige donde se guardan archivos
    'default' => env('FILESYSTEM_DISK', 'local'),

    // lista carpetas y servicios para archivos
    'disks' => [

        // guarda archivos privados
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        // guarda imagenes publicas
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => rtrim(env('APP_URL', 'http://localhost'), '/').'/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        // guarda archivos en amazon si se configura
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    // publica archivos guardados en storage
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
