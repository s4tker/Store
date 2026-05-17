<?php

namespace App\Http\Controllers;

use App\Mail\OtpVerificationMail;
use App\Models\PendingUserVerification;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private const OTP_EXPIRATION_MINUTES = 10;

    public function showLogin(Request $request)
    {
        return view('Login.login', [
            'RedirectTo' => $request->query('redirect', ''),
        ]);
    }

    public function resendOtp(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $verification = PendingUserVerification::where('Email', $email)->first();

        if (! $verification) {
            return response()->json([
                'success' => false,
                'message' => 'No hay una verificacion pendiente para este correo',
            ], 422);
        }

        if (User::where('Correo', $email)->exists()) {
            $verification->delete();

            return response()->json([
                'success' => false,
                'message' => 'Este correo ya esta registrado',
            ], 422);
        }

        try {
            $otpCode = (string) random_int(100000, 999999);

            $verification->update([
                'OtpCode' => Hash::make($otpCode),
                'ExpiresAt' => now()->addMinutes(self::OTP_EXPIRATION_MINUTES),
                'CreatedAt' => now(),
            ]);

            Mail::to($email)->send(new OtpVerificationMail(
                $otpCode,
                $email,
                self::OTP_EXPIRATION_MINUTES,
            ));

            return response()->json([
                'success' => true,
                'message' => 'Codigo reenviado al correo.',
            ]);
        } catch (\Exception $e) {
            Log::error('No se pudo reenviar el codigo OTP', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo reenviar el codigo',
            ], 422);
        }
    }

    public function showOtp(Request $request)
    {
        $email = mb_strtolower(trim((string) session('otp_email', '')));

        if ($email === '' || ! PendingUserVerification::where('Email', $email)->exists()) {
            return redirect()->route('login');
        }

        return view('Login.otp', [
            'Email' => $email,
            'RedirectTo' => session('otp_redirect', ''),
        ]);
    }

    public function checkEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $user = User::where('Correo', $email)->first();

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
                $email = mb_strtolower(trim($data['email']));

                if (User::where('Correo', $email)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este correo ya esta registrado',
                    ], 422);
                }

                $otpCode = (string) random_int(100000, 999999);

                PendingUserVerification::updateOrCreate(
                    ['Email' => $email],
                    [
                        'Password' => Hash::make($data['password']),
                        'OtpCode' => Hash::make($otpCode),
                        'ExpiresAt' => now()->addMinutes(self::OTP_EXPIRATION_MINUTES),
                        'CreatedAt' => now(),
                    ],
                );

                Mail::to($email)->send(new OtpVerificationMail(
                    $otpCode,
                    $email,
                    self::OTP_EXPIRATION_MINUTES,
                ));

                $redirect = $this->resolveRedirect($request);
                session([
                    'otp_email' => $email,
                    'otp_redirect' => $redirect,
                ]);

                return response()->json([
                    'success' => true,
                    'requires_otp' => true,
                    'message' => 'Codigo enviado al correo.',
                    'redirect' => route('auth.otp.show'),
                ]);
            } catch (\Exception $e) {
                Log::error('No se pudo enviar el codigo OTP', [
                    'email' => $data['email'] ?? null,
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo enviar el codigo de verificacion',
                ], 422);
            }
        }

        $email = mb_strtolower(trim($data['email']));
        $user = User::where('Correo', $email)->first();

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

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'digits:6'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $verification = PendingUserVerification::where('Email', $email)->first();

        if (! $verification) {
            return response()->json([
                'success' => false,
                'message' => 'No hay una verificacion pendiente para este correo',
            ], 422);
        }

        if ($verification->ExpiresAt->isPast()) {
            $verification->delete();

            return response()->json([
                'success' => false,
                'message' => 'El codigo expiro. Registra tu cuenta nuevamente',
            ], 422);
        }

        if (! Hash::check($data['otp_code'], $verification->OtpCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Codigo incorrecto',
            ], 422);
        }

        if (User::where('Correo', $email)->exists()) {
            $verification->delete();

            return response()->json([
                'success' => false,
                'message' => 'Este correo ya esta registrado',
            ], 422);
        }

        $user = DB::transaction(function () use ($verification, $email) {
            $alias = str(explode('@', $email)[0])->replace(['.', ' ', '_'], '-')->lower()->value();
            $user = User::create([
                'Alias' => $alias,
                'Nombre' => null,
                'Correo' => $email,
                'Password' => $verification->Password,
            ]);

            $role = Role::query()
                ->whereRaw('LOWER(Nombre) in (?, ?, ?)', ['usuario', 'cliente', 'user'])
                ->orderByRaw("CASE WHEN LOWER(Nombre) = 'usuario' THEN 0 WHEN LOWER(Nombre) = 'cliente' THEN 1 ELSE 2 END")
                ->first();

            if ($role) {
                $user->roles()->attach($role->Id);
            }

            $verification->delete();

            return $user;
        });

        Auth::login($user);
        session()->forget(['otp_email', 'otp_redirect']);

        return response()->json([
            'success' => true,
            'message' => 'Cuenta verificada correctamente.',
            'redirect' => $this->resolveRedirect($request),
        ]);
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
