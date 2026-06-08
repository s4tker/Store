<?php

// este controlador atiende pantallas y acciones del sistema
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// esta clase controla la pantalla de compra
class CompraController extends Controller
{
    // muestra carrito direccion y comprobante antes de pagar

    public function formulario(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login', [
                'redirect' => $request->getRequestUri(),
            ]);
        }

        $usuarioCompra = Auth::user();
        $direccionesCompra = $usuarioCompra
            ? $usuarioCompra->direcciones()->orderByDesc('Id')->get()
            : collect();

        return view('Compras.formulario', [
            'UsuarioCompra' => $usuarioCompra,
            'DireccionesCompra' => $direccionesCompra,
        ]);
    }
}
