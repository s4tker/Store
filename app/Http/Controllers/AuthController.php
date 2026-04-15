<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function checkEmail(Request $request)
    {
        $user = User::where('Correo', $request->email)->first();
        return response()->json(['exists' => (bool)$user]);
    }

    public function authenticate(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $mode = $request->mode;

        if ($mode === 'register') {
            try {
                return DB::transaction(function () use ($email, $password) {
                    $user = User::create([
                        'Alias'    => explode('@', $email)[0],
                        'Correo'   => $email,
                        'Password' => Hash::make($password),
                        'Nombre'   => null,
                    ]);

                    $role = Role::where('Nombre', 'Cliente')->first();
                    if ($role) {
                        $user->roles()->attach($role->Id);
                    }

                    Auth::login($user);
                    return response()->json(['success' => true]);
                });
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error al crear cuenta']);
            }
        } else {
            $user = User::where('Correo', $email)->first();

            if ($user && Hash::check($password, $user->Password)) {
                Auth::login($user);
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Clave incorrecta']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
