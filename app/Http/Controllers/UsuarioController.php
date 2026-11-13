<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->get();
        $roles = Rol::where('estado', 1)->get();
        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:120',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico',
            'contrasena' => 'required|min:6',
            'id_rol' => 'required|exists:roles,id',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'correo_electronico' => $request->correo_electronico,
            'contrasena' => Hash::make($request->contrasena),
            'id_rol' => $request->id_rol,
            'estado' => 1,
        ]);

        return back()->with('success', 'Usuario registrado correctamente.');
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:120',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico,' . $usuario->id,
            'id_rol' => 'required|exists:roles,id',
        ]);

        $data = $request->only(['nombre', 'correo_electronico', 'id_rol']);
        if ($request->filled('contrasena')) {
            $data['contrasena'] = Hash::make($request->contrasena);
        }
        $usuario->update($data);

        return back()->with('success', 'Usuario actualizado.');
    }

    // No elimina: activa o desactiva estado
    public function toggleEstado(Usuario $usuario)
    {
        $usuario->estado = !$usuario->estado;
        $usuario->save();
        return back()->with('success', 'Estado del usuario actualizado.');
    }
}
