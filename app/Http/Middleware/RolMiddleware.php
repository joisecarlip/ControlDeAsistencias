<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, $rol)
    {
        $user = Auth::guard('usuarios')->user();

        if (!$user || $user->rol !== $rol) {
            abort(403, 'Acceso no autorizado');
        }

        return $next($request);
    }
}
