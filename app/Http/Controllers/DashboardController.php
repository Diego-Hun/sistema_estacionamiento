<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EspacioEstacionamiento;
use App\Models\Boleto;
use App\Models\Vehiculo;
use App\Models\Cliente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEspacios = EspacioEstacionamiento::where('estado', 1)->count();
        $ocupados = EspacioEstacionamiento::where('estado', 1)->where('esta_ocupado', 1)->count();
        $disponibles = $totalEspacios - $ocupados;
        $ingresosHoy = Boleto::where('esta_pagado', 1)->whereDate('hora_salida', Carbon::today())->sum('monto_total');
        $vehiculosActivos = Boleto::whereNull('hora_salida')->count();
        $abonados = Cliente::where('es_suscriptor', 1)->where('estado', 1)->count();

        return view('dashboard.index', compact('totalEspacios', 'ocupados', 'disponibles', 'ingresosHoy', 'vehiculosActivos', 'abonados'));
    }
}
