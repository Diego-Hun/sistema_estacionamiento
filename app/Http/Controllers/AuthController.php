<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        $usuario = Usuario::where('correo_electronico', $request->correo_electronico)->first();

        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            return back()->withErrors(['correo_electronico' => 'Credenciales incorrectas.'])->withInput();
        }

        if (!$usuario->estado) {
            return back()->withErrors(['correo_electronico' => 'Su cuenta está desactivada. Contacte al administrador.']);
        }

        Auth::login($usuario);
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
