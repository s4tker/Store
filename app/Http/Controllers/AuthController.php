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
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('Correo', $data['email'])->first();

        return response()->json(['exists' => (bool) $user]);
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
            'mode' => ['required', 'in:login,register'],
        ]);

        if ($data['mode'] === 'register') {
            try {
                return DB::transaction(function () use ($data) {
                    $name = str(explode('@', $data['email'])[0])->replace(['.', '_', '-'], ' ')->title()->value();
                    $user = User::create([
                        'Nombre' => $name,
                        'Correo' => $data['email'],
                        'Password' => Hash::make($data['password']),
                    ]);

                    $role = Role::query()
                        ->whereRaw('LOWER(Nombre) = ?', ['cliente'])
                        ->first();

                    if ($role) {
                        $user->roles()->attach($role->Id);
                    }

                    Auth::login($user);

                    return response()->json([
                        'success' => true,
                        'message' => 'Cuenta creada correctamente.',
                    ]);
                });
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear cuenta',
                ], 422);
            }
        }

        $user = User::where('Correo', $data['email'])->first();

        if ($user && Hash::check($data['password'], $user->Password)) {
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Sesión iniciada correctamente.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Clave incorrecta',
        ], 422);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
