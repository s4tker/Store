<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $isAdmin = $user && ($user->hasRole('admin') || $user->hasRole('administrador'));

        if (! $isAdmin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para acceder a esta sección.',
                ], 403);
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}
