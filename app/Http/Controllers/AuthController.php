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
    public function showLogin(Request $request)
    {
        return view('Login.login', [
            'RedirectTo' => $request->query('redirect', ''),
        ]);
    }

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
                return DB::transaction(function () use ($data, $request) {
                    $alias = str(explode('@', $data['email'])[0])->replace(['.', ' ', '_'], '-')->lower()->value();
                    $user = User::create([
                        'Alias' => $alias,
                        'Nombre' => null,
                        'Correo' => $data['email'],
                        'Password' => Hash::make($data['password']),
                    ]);

                    $role = Role::query()
                        ->whereRaw('LOWER(Nombre) in (?, ?, ?)', ['usuario', 'cliente', 'user'])
                        ->orderByRaw("CASE WHEN LOWER(Nombre) = 'usuario' THEN 0 WHEN LOWER(Nombre) = 'cliente' THEN 1 ELSE 2 END")
                        ->first();

                    if ($role) {
                        $user->roles()->attach($role->Id);
                    }

                    Auth::login($user);

                    return response()->json([
                        'success' => true,
                        'message' => 'Cuenta creada correctamente.',
                        'redirect' => $this->resolveRedirect($request),
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
                'redirect' => $this->resolveRedirect($request),
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

    protected function resolveRedirect(Request $request): string
    {
        $redirect = (string) $request->input('redirect', '');

        if ($redirect !== '' && str_starts_with($redirect, '/')) {
            return $redirect;
        }

        return route('home');
    }
}
