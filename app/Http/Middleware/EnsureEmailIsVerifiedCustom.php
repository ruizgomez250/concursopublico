<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerifiedCustom
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            // Redirige al usuario a la página de login con un mensaje personalizado.
            return redirect('login')->with('error', 'Tu cuenta necesita verificación. Revisa tu correo electrónico para el enlace de verificación.');
        }

        return $next($request);
    }
}
