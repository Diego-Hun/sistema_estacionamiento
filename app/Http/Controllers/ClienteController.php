<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('vehiculos')->get();
        return view('clientes.index', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|string|max:30',
            'correo_electronico' => 'nullable|email',
            'convenio' => 'nullable|string|max:150',
            'fecha_vencimiento' => 'nullable|date',
        ]);

        Cliente::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo_electronico' => $request->correo_electronico,
            'es_suscriptor' => $request->has('es_suscriptor') ? 1 : 0,
            'convenio' => $request->convenio,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'estado' => 1,
        ]);

        return back()->with('success', 'Cliente / Abonado registrado.');
    }

    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'correo_electronico' => $request->correo_electronico,
            'es_suscriptor' => $request->has('es_suscriptor') ? 1 : 0,
            'convenio' => $request->convenio,
            'fecha_vencimiento' => $request->fecha_vencimiento,
        ]);
        return back()->with('success', 'Datos de cliente actualizados.');
    }

    // No elimina: activa o desactiva estado
    public function toggleEstado(Cliente $cliente)
    {
        $cliente->estado = !$cliente->estado;
        $cliente->save();
        return back()->with('success', 'Estado del cliente actualizado.');
    }
}
