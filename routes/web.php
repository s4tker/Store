<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// HOME
Route::get('/', [HomeController::class, 'index'])->name('home');

// AUTH (AJAX)
Route::post('/auth/check', [AuthController::class, 'checkEmail'])->name('auth.check');
Route::post('/auth/process', [AuthController::class, 'authenticate'])->name('auth.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ACCOUNT (PROTEGIDO)
Route::middleware(['auth'])->group(function () {

    Route::get('/account', [AccountController::class, 'index'])
        ->name('account');

    Route::get('/account/edit', [AccountController::class, 'edit'])
        ->name('account.edit');

    Route::get('/account/password', [AccountController::class, 'passwordForm'])
        ->name('account.password');

    Route::post('/account/update', [AccountController::class, 'update'])
        ->name('account.update');

    Route::post('/account/password', [AccountController::class, 'changePassword'])
        ->name('account.password.update');

    Route::post('/account/addresses', [AccountController::class, 'storeAddress'])
        ->name('account.addresses.store');

    Route::delete('/account/addresses/{id}', [AccountController::class, 'deleteAddress'])
        ->name('account.addresses.delete');
});