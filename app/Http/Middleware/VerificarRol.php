<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !Auth::user()->estado) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['correo_electronico' => 'Usuario inactivo o no autenticado.']);
        }

        $rolUsuario = Auth::user()->rol->nombre ?? '';
        if (!in_array($rolUsuario, $roles)) {
            abort(403, 'No tienes permisos para acceder a este módulo.');
        }

        return $next($request);
    }
}
