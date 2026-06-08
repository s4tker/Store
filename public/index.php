<?php

// archivo que abre la aplicacion en el navegador
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// revisa si la aplicacion esta en mantenimiento
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// carga composer
require __DIR__.'/../vendor/autoload.php';

// inicia laravel y atiende la solicitud
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
