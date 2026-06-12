<?php

// este controlador atiende pantallas y acciones del sistema
namespace App\Http\Controllers;

use App\Mail\OtpVerificationMail;
use App\Mail\PasswordResetCodeMail;
use App\Models\PendingUserVerification;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\Role;
use App\Services\BrevoMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

// esta clase controla acceso registro y codigo
class AuthController extends Controller
{
    private const OTP_EXPIRATION_MINUTES = 10;
    private const PASSWORD_RESET_EXPIRATION_MINUTES = 10;

    public function __construct(private readonly BrevoMailService $mailService)
    {
    }
    // muestra formulario de login y registro

    public function showLogin(Request $request)
    {
        return view('Login.login', [
            'RedirectTo' => $request->query('redirect', ''),
        ]);
    }
    // reenvia el codigo de verificacion

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

            $this->mailService->send($email, new OtpVerificationMail(
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
    // muestra formulario para escribir el codigo otp

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
    // revisa si el correo ya existe

    public function checkEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $user = User::where('Correo', $email)->first();

        return response()->json(['exists' => (bool) $user]);
    }
    // muestra formulario para pedir codigo de recuperacion

    public function showForgotPassword(Request $request)
    {
        return view('Login.forgot-password', [
            'Email' => mb_strtolower(trim((string) $request->query('email', ''))),
            'RedirectTo' => $request->query('redirect', ''),
        ]);
    }
    // envia codigo para restablecer contraseña

    public function sendPasswordResetCode(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'redirect' => ['nullable', 'string'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $user = User::where('Correo', $email)->first();

        if (! $user) {
            return back()
                ->withInput(['email' => $email])
                ->withErrors(['email' => 'No encontramos una cuenta con ese correo.']);
        }

        try {
            $code = (string) random_int(100000, 999999);

            PasswordReset::updateOrCreate(
                ['Correo' => $email],
                [
                    'Token' => Hash::make($code),
                    'CreatedAt' => now(),
                ],
            );

            $this->mailService->send($email, new PasswordResetCodeMail(
                $code,
                $email,
                self::PASSWORD_RESET_EXPIRATION_MINUTES,
            ));

            session([
                'password_reset_email' => $email,
                'password_reset_redirect' => $this->normalizeRedirect($data['redirect'] ?? ''),
            ]);

            return redirect()
                ->route('password.reset.form')
                ->with('status', 'Codigo enviado a tu correo.');
        } catch (\Exception $e) {
            Log::error('No se pudo enviar el codigo de recuperacion', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput(['email' => $email])
                ->withErrors(['email' => 'No se pudo enviar el codigo. Revisa la configuracion de correo.']);
        }
    }
    // muestra formulario para escribir codigo y nueva clave

    public function showResetPassword(Request $request)
    {
        $email = mb_strtolower(trim((string) (session('password_reset_email') ?: $request->query('email', ''))));

        if ($email === '' || ! PasswordReset::where('Correo', $email)->exists()) {
            return redirect()->route('password.forgot', ['email' => $email]);
        }

        return view('Login.reset-password', [
            'Email' => $email,
            'RedirectTo' => session('password_reset_redirect', ''),
        ]);
    }
    // valida codigo y cambia contraseña

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'redirect' => ['nullable', 'string'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $reset = PasswordReset::where('Correo', $email)->latest('CreatedAt')->first();

        if (! $reset) {
            return back()->withErrors(['code' => 'Solicita un codigo nuevo.']);
        }

        if ($reset->CreatedAt && $reset->CreatedAt->copy()->addMinutes(self::PASSWORD_RESET_EXPIRATION_MINUTES)->isPast()) {
            $reset->delete();

            return redirect()
                ->route('password.forgot', ['email' => $email])
                ->withErrors(['email' => 'El codigo expiro. Solicita uno nuevo.']);
        }

        if (! Hash::check($data['code'], $reset->Token)) {
            return back()
                ->withInput(['email' => $email])
                ->withErrors(['code' => 'Codigo incorrecto.']);
        }

        $user = User::where('Correo', $email)->first();

        if (! $user) {
            $reset->delete();

            return redirect()
                ->route('password.forgot')
                ->withErrors(['email' => 'No encontramos una cuenta con ese correo.']);
        }

        $user->Password = Hash::make($data['password']);
        $user->save();
        $reset->delete();

        Auth::login($user);
        session()->forget(['password_reset_email', 'password_reset_redirect']);

        return redirect($this->normalizeRedirect($data['redirect'] ?? ''))
            ->with('status', 'Contraseña actualizada correctamente.');
    }
    // valida credenciales o datos nuevos de usuario

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
            'mode' => ['required', 'in:login,register'],
        ]);

        $email = mb_strtolower(trim($data['email']));
        $user = User::where('Correo', $email)->first();

        if ($user) {
            if (Hash::check($data['password'], $user->Password)) {
                Auth::login($user);

                return response()->json([
                    'success' => true,
                    'message' => 'Sesion iniciada correctamente.',
                    'redirect' => $this->resolveRedirect($request),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Clave incorrecta',
            ], 422);
        }

        if ($data['mode'] === 'login') {
            return response()->json([
                'success' => false,
                'message' => 'No existe una cuenta con ese correo',
            ], 422);
        }

        if ($data['mode'] === 'register') {
            try {
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

                $this->mailService->send($email, new OtpVerificationMail(
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
    // verifica el codigo enviado

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
    // cierra la sesion del usuario

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
    // envia al panel o a la tienda segun su rol

    protected function resolveRedirect(Request $request): string
    {
        $redirect = (string) $request->input('redirect', '');

        return $this->normalizeRedirect($redirect);
    }
    // evita redirecciones externas

    protected function normalizeRedirect(string $redirect): string
    {
        if ($redirect !== '' && str_starts_with($redirect, '/') && ! str_starts_with($redirect, '//')) {
            return $redirect;
        }

        return route('home');
    }
}
