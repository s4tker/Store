<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// HOME
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/producto/{slug}', [HomeController::class, 'showProduct'])->name('product.show');
Route::get('/compras/formulario', [CompraController::class, 'formulario'])->name('compras.formulario');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// AUTH (AJAX)
Route::post('/auth/check', [AuthController::class, 'checkEmail'])->name('auth.check');
Route::post('/auth/process', [AuthController::class, 'authenticate'])->name('auth.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/productos', [AdminController::class, 'products'])->name('admin.productos.index');
    Route::get('/admin/usuarios', [AdminController::class, 'users'])->name('admin.usuarios.index');
    Route::get('/admin/estadisticas', [AdminController::class, 'statistics'])->name('admin.estadisticas.index');
    Route::post('/admin/productos/store', [AdminController::class, 'storeProduct'])->name('admin.productos.store');
    Route::put('/admin/productos/{producto}', [AdminController::class, 'updateProduct'])->name('admin.productos.update');
    Route::delete('/admin/productos/{producto}', [AdminController::class, 'destroyProduct'])->name('admin.productos.destroy');
    Route::post('/admin/categorias/store', [AdminController::class, 'storeCategory'])->name('admin.categorias.store');
    Route::put('/admin/categorias/{categoria}', [AdminController::class, 'updateCategory'])->name('admin.categorias.update');
    Route::delete('/admin/categorias/{categoria}', [AdminController::class, 'destroyCategory'])->name('admin.categorias.destroy');
    Route::post('/admin/marcas/store', [AdminController::class, 'storeBrand'])->name('admin.marcas.store');
    Route::put('/admin/marcas/{marca}', [AdminController::class, 'updateBrand'])->name('admin.marcas.update');
    Route::delete('/admin/marcas/{marca}', [AdminController::class, 'destroyBrand'])->name('admin.marcas.destroy');
    Route::post('/admin/usuarios/store', [AdminController::class, 'storeUser'])->name('admin.usuarios.store');
    Route::put('/admin/usuarios/{usuario}', [AdminController::class, 'updateUser'])->name('admin.usuarios.update');
    Route::delete('/admin/usuarios/{usuario}', [AdminController::class, 'destroyUser'])->name('admin.usuarios.destroy');
});

// ACCOUNT (PROTEGIDO)
Route::middleware(['auth'])->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'index'])
        ->name('pedidos.index');

    Route::post('/pedidos', [PedidoController::class, 'store'])
        ->name('pedidos.store');

    Route::get('/clientes/dni/{dni}', [PedidoController::class, 'buscarClientePorDni'])
        ->name('clientes.buscar-dni');

    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])
        ->name('pedidos.show');

    Route::post('/pedidos/{id}/cancelar', [PedidoController::class, 'cancelar'])
        ->name('pedidos.cancelar');

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
