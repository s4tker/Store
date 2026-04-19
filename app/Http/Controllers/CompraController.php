<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
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
