<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Ruta principal (donde está tu index.blade.php)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de Autenticación (AJAX)
Route::post('/auth/check', [AuthController::class, 'checkEmail'])->name('auth.check');
Route::post('/auth/process', [AuthController::class, 'authenticate'])->name('auth.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (Mi Cuenta)
Route::middleware(['auth'])->group(function () {
    Route::get('/mi-cuenta', function() {
        return view('account'); // Aquí crearás la vista para editar DNI, RUC, etc.
    })->name('account');
});
