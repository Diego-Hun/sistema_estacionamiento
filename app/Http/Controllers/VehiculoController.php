<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Cliente;
use App\Models\TipoVehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::with(['cliente', 'tipoVehiculo'])->get();
        $clientes = Cliente::where('estado', 1)->get();
        $tipos = TipoVehiculo::where('estado', 1)->get();
        return view('vehiculos.index', compact('vehiculos', 'clientes', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:20|unique:vehiculos,placa',
            'id_tipo_vehiculo' => 'required|exists:tipos_vehiculo,id',
            'marca' => 'required|string|max:80',
            'modelo' => 'required|string|max:80',
            'color' => 'required|string|max:50',
            'id_cliente' => 'nullable|exists:clientes,id',
        ]);

        Vehiculo::create([
            'placa' => strtoupper(trim($request->placa)),
            'id_cliente' => $request->id_cliente,
            'id_tipo_vehiculo' => $request->id_tipo_vehiculo,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'color' => $request->color,
            'estado' => 1,
        ]);

        return back()->with('success', 'Vehículo registrado correctamente.');
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $request->validate([
            'placa' => 'required|string|max:20|unique:vehiculos,placa,' . $vehiculo->id,
            'id_tipo_vehiculo' => 'required|exists:tipos_vehiculo,id',
            'marca' => 'required|string|max:80',
            'modelo' => 'required|string|max:80',
            'color' => 'required|string|max:50',
        ]);

        $vehiculo->update([
            'placa' => strtoupper(trim($request->placa)),
            'id_cliente' => $request->id_cliente,
            'id_tipo_vehiculo' => $request->id_tipo_vehiculo,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'color' => $request->color,
        ]);

        return back()->with('success', 'Vehículo actualizado.');
    }

    // No elimina: activa o desactiva estado
    public function toggleEstado(Vehiculo $vehiculo)
    {
        $vehiculo->estado = !$vehiculo->estado;
        $vehiculo->save();
        return back()->with('success', 'Estado del vehículo actualizado.');
    }
}
