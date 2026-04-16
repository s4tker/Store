<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// HOME
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/producto/{slug}', [HomeController::class, 'showProduct'])->name('product.show');

// AUTH (AJAX)
Route::post('/auth/check', [AuthController::class, 'checkEmail'])->name('auth.check');
Route::post('/auth/process', [AuthController::class, 'authenticate'])->name('auth.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/productos/store', [AdminController::class, 'storeProduct'])->name('admin.productos.store');
    Route::put('/admin/productos/{producto}', [AdminController::class, 'updateProduct'])->name('admin.productos.update');
    Route::delete('/admin/productos/{producto}', [AdminController::class, 'destroyProduct'])->name('admin.productos.destroy');
    Route::post('/admin/categorias/store', [AdminController::class, 'storeCategory'])->name('admin.categorias.store');
    Route::put('/admin/categorias/{categoria}', [AdminController::class, 'updateCategory'])->name('admin.categorias.update');
    Route::delete('/admin/categorias/{categoria}', [AdminController::class, 'destroyCategory'])->name('admin.categorias.destroy');
    Route::post('/admin/marcas/store', [AdminController::class, 'storeBrand'])->name('admin.marcas.store');
    Route::put('/admin/marcas/{marca}', [AdminController::class, 'updateBrand'])->name('admin.marcas.update');
    Route::delete('/admin/marcas/{marca}', [AdminController::class, 'destroyBrand'])->name('admin.marcas.destroy');
});

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
