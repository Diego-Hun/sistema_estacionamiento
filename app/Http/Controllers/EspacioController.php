<?php

namespace App\Http\Controllers;

use App\Models\EspacioEstacionamiento;
use Illuminate\Http\Request;

class EspacioController extends Controller
{
    public function index()
    {
        $espacios = EspacioEstacionamiento::orderBy('piso')->orderBy('sector')->orderBy('numero')->get();
        return view('espacios.index', compact('espacios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'piso' => 'required|string|max:30',
            'sector' => 'required|string|max:30',
            'numero' => 'required|string|max:20',
        ]);

        EspacioEstacionamiento::create([
            'piso' => $request->piso,
            'sector' => $request->sector,
            'numero' => strtoupper($request->numero),
            'esta_ocupado' => 0,
            'estado' => 1,
        ]);

        return back()->with('success', 'Celda / espacio creado.');
    }

    public function update(Request $request, EspacioEstacionamiento $espacio)
    {
        $espacio->update($request->only(['piso', 'sector', 'numero']));
        return back()->with('success', 'Espacio actualizado.');
    }

    // No elimina: activa o desactiva estado
    public function toggleEstado(EspacioEstacionamiento $espacio)
    {
        if ($espacio->esta_ocupado) {
            return back()->withErrors(['error' => 'No se puede desactivar una celda ocupada.']);
        }
        $espacio->estado = !$espacio->estado;
        $espacio->save();
        return back()->with('success', 'Estado de celda actualizado.');
    }
}
