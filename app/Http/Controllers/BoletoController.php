<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\Vehiculo;
use App\Models\TipoVehiculo;
use App\Models\EspacioEstacionamiento;
use App\Models\MetodoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BoletoController extends Controller
{
    public function index()
    {
        $boletosActivos = Boleto::with(['vehiculo.tipoVehiculo', 'vehiculo.cliente', 'espacio', 'usuario'])
            ->whereNull('hora_salida')
            ->orderByDesc('hora_entrada')
            ->get();

        $historialBoletos = Boleto::with(['vehiculo.tipoVehiculo', 'espacio', 'metodoPago'])
            ->whereNotNull('hora_salida')
            ->orderByDesc('hora_salida')
            ->limit(100)
            ->get();

        $espaciosDisponibles = EspacioEstacionamiento::where('estado', 1)->where('esta_ocupado', 0)->get();
        $tiposVehiculo = TipoVehiculo::where('estado', 1)->get();
        $metodosPago = MetodoPago::where('estado', 1)->get();

        return view('boletos.index', compact('boletosActivos', 'historialBoletos', 'espaciosDisponibles', 'tiposVehiculo', 'metodosPago'));
    }

    // Registro de entrada (crea vehículo al vuelo si no existe)
    public function registrarEntrada(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:20',
            'id_espacio_estacionamiento' => 'required|exists:espacios_estacionamiento,id',
            'id_tipo_vehiculo' => 'required|exists:tipos_vehiculo,id',
        ]);

        $placa = strtoupper(trim($request->placa));
        $vehiculo = Vehiculo::firstOrCreate(
            ['placa' => $placa],
            [
                'id_tipo_vehiculo' => $request->id_tipo_vehiculo,
                'marca' => $request->marca ?? 'Genérico',
                'modelo' => $request->modelo ?? 'Estándar',
                'color' => $request->color ?? 'No especificado',
                'estado' => 1,
            ]
        );

        if (!$vehiculo->estado) {
            return back()->withErrors(['placa' => 'Este vehículo se encuentra desactivado en el sistema.']);
        }

        $yaAdentro = Boleto::where('id_vehiculo', $vehiculo->id)->whereNull('hora_salida')->exists();
        if ($yaAdentro) {
            return back()->withErrors(['placa' => 'El vehículo ya tiene un ingreso activo sin salida.']);
        }

        $espacio = EspacioEstacionamiento::findOrFail($request->id_espacio_estacionamiento);
        if ($espacio->esta_ocupado || !$espacio->estado) {
            return back()->withErrors(['id_espacio_estacionamiento' => 'La celda seleccionada no está disponible.']);
        }

        $boleto = Boleto::create([
            'codigo_ticket' => 'TKT-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(3)),
            'id_vehiculo' => $vehiculo->id,
            'id_espacio_estacionamiento' => $espacio->id,
            'id_usuario' => Auth::id(),
            'hora_entrada' => Carbon::now(),
            'esta_pagado' => 0,
        ]);

        $espacio->update(['esta_ocupado' => 1]);

        return back()->with('success', 'Entrada registrada. Ticket emitido: ' . $boleto->codigo_ticket);
    }

    // Previsualizar cálculo de tarifa vía AJAX antes de cobrar
    public function calcularTarifa(Request $request)
    {
        $boleto = Boleto::with(['vehiculo.tipoVehiculo', 'vehiculo.cliente'])->findOrFail($request->id_boleto);
        $ahora = Carbon::now();
        $minutos = max(1, $boleto->hora_entrada->diffInMinutes($ahora));

        $cliente = $boleto->vehiculo->cliente;
        $esAbonadoVigente = $cliente && $cliente->es_suscriptor && $cliente->estado &&
            (!$cliente->fecha_vencimiento || Carbon::parse($cliente->fecha_vencimiento)->isFuture());

        if ($esAbonadoVigente) {
            $monto = 0.00;
        } else {
            $horas = ceil($minutos / 60);
            $monto = $horas * $boleto->vehiculo->tipoVehiculo->tarifa_hora;
        }

        return response()->json([
            'codigo_ticket' => $boleto->codigo_ticket,
            'placa' => $boleto->vehiculo->placa,
            'hora_entrada' => $boleto->hora_entrada->format('d/m/Y H:i'),
            'hora_salida' => $ahora->format('d/m/Y H:i'),
            'minutos' => $minutos,
            'es_abonado' => $esAbonadoVigente,
            'monto_total' => number_format($monto, 2, '.', ''),
        ]);
    }

    // Registrar salida y cobro en Caja
    public function registrarSalidaCobro(Request $request)
    {
        $request->validate([
            'id_boleto' => 'required|exists:boletos,id',
            'id_metodo_pago' => 'required|exists:metodos_pago,id',
            'monto_total' => 'required|numeric|min:0',
        ]);

        $boleto = Boleto::with('espacio')->findOrFail($request->id_boleto);
        if ($boleto->hora_salida) {
            return back()->withErrors(['error' => 'Este ticket ya fue cerrado y cobrado.']);
        }

        $salida = Carbon::now();
        $minutos = max(1, $boleto->hora_entrada->diffInMinutes($salida));

        $boleto->update([
            'hora_salida' => $salida,
            'total_tiempo_minutos' => $minutos,
            'id_metodo_pago' => $request->id_metodo_pago,
            'monto_total' => $request->monto_total,
            'esta_pagado' => 1,
        ]);

        $boleto->espacio->update(['esta_ocupado' => 0]);

        return back()->with('success', 'Salida y cobro registrados correctamente (Ticket ' . $boleto->codigo_ticket . ').');
    }

    public function ticketDigital(Boleto $boleto)
    {
        $boleto->load(['vehiculo.tipoVehiculo', 'espacio', 'usuario', 'metodoPago']);
        return view('boletos.ticket', compact('boleto'));
    }
}
