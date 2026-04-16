<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Direccion;
use App\Models\PasswordReset;

class AccountController extends Controller
{
    //  Dashboard "Mi Cuenta"
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->direcciones ?? collect();
        return view('account.index', compact('user', 'addresses'));
    }

    // Formulario editar perfil
    public function edit()
    {
        $user = Auth::user();
        return view('account.edit', compact('user'));
    }

    //  Actualizar datos
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'Nombre' => 'required|max:60',
            'Apellidos' => 'required|max:100',
            'Correo' => 'required|email|unique:Usuarios,Correo,' . $user->Id . ',Id',
            'Telefono' => 'nullable|size:9',
            'Dni' => 'required|max:15|unique:Usuarios,Dni,' . $user->Id . ',Id',
            'Ruc' => 'nullable|max:15|unique:Usuarios,Ruc,' . $user->Id . ',Id'
        ]);

        $user->update($request->only(
            'Nombre',
            'Apellidos',
            'Correo',
            'Telefono',
            'Dni',
            'Ruc'
        ));

        return redirect()->route('account')
            ->with('success', 'Datos actualizados correctamente');
    }

    //  Formulario cambiar contraseña
    public function passwordForm()
    {
        return view('account.password');
    }

    //  Cambiar contraseña
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('Id', Auth::id())->firstOrFail();

        // Validar contraseña actual
        if (!Hash::check($request->current_password, $user->Password)) {
            return back()->withErrors([
                'current_password' => __('auth.password_incorrect')
            ]);
        }

        // Update password
        $user->update([
            'Password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }

    // 📍 Listar direcciones
    public function addresses()
    {
        $addresses = Auth::user()->direcciones ?? collect();
        return view('account.addresses', compact('addresses'));
    }

    //  Guardar nueva dirección
    public function storeAddress(Request $request)
    {
        $request->validate([
            'Pais' => 'required|max:40',
            'Region' => 'required|max:50',
            'Ciudad' => 'required|max:50',
            'Direccion' => 'required|max:120',
            'Referencia' => 'nullable|max:120'
        ]);

        Direccion::create([
            'UsuarioId' => Auth::id(),
            'Pais' => $request->Pais,
            'Region' => $request->Region,
            'Ciudad' => $request->Ciudad,
            'Direccion' => $request->Direccion,
            'Referencia' => $request->Referencia
        ]);

        return back()->with('success', 'Dirección agregada correctamente');
    }

    // Actualizar dirección
    public function updateAddress(Request $request, $id)
    {
        $direccion = Direccion::where('Id', $id)
            ->where('UsuarioId', Auth::id())
            ->firstOrFail();

        $request->validate([
            'Pais' => 'required|max:40',
            'Region' => 'required|max:50',
            'Ciudad' => 'required|max:50',
            'Direccion' => 'required|max:120',
            'Referencia' => 'nullable|max:120'
        ]);

        $direccion->update($request->only(
            'Pais',
            'Region',
            'Ciudad',
            'Direccion',
            'Referencia'
        ));

        return back()->with('success', 'Dirección actualizada');
    }

    //  Eliminar dirección
    public function deleteAddress($id)
    {
        $direccion = Direccion::where('Id', $id)
            ->where('UsuarioId', Auth::id())
            ->firstOrFail();

        $direccion->delete();

        return back()->with('success', 'Dirección eliminada');
    }

    //  (Opcional avanzado) Generar token para reset
    public function generateResetToken()
    {
        $user = Auth::user();

        $token = Str::random(60);

        PasswordReset::create([
            'Correo' => $user->Correo,
            'Token' => $token
        ]);

        // Aquí luego puedes enviar correo

        return back()->with('success', 'Token generado (modo prueba)');
    }
}
