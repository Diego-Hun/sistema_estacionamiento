<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\EspacioEstacionamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    // Endpoint AJAX para filtros dinámicos sin recargar página
    public function filtrarAjax(Request $request)
    {
        $query = Boleto::with(['vehiculo.tipoVehiculo', 'espacio', 'metodoPago'])
            ->where('esta_pagado', 1);

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('hora_salida', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('hora_salida', '<=', $request->fecha_fin);
        }
        if ($request->filled('placa')) {
            $placa = $request->placa;
            $query->whereHas('vehiculo', fn($q) => $q->where('placa', 'like', "%{$placa}%"));
        }

        $boletos = $query->orderByDesc('hora_salida')->get();
        $totalIngresos = $boletos->sum('monto_total');

        $frecuentes = Boleto::select('id_vehiculo', DB::raw('COUNT(*) as visitas'), DB::raw('SUM(monto_total) as total_pagado'))
            ->with('vehiculo.tipoVehiculo')
            ->groupBy('id_vehiculo')
            ->orderByDesc('visitas')
            ->limit(5)
            ->get();

        $ocupados = EspacioEstacionamiento::where('estado', 1)->where('esta_ocupado', 1)->count();
        $totales = EspacioEstacionamiento::where('estado', 1)->count();

        return response()->json([
            'boletos' => $boletos,
            'total_ingresos' => number_format($totalIngresos, 2),
            'frecuentes' => $frecuentes,
            'ocupacion' => ['ocupados' => $ocupados, 'totales' => $totales],
        ]);
    }

    // Exportación a PDF (requiere barryvdh/laravel-dompdf)
    public function exportarPdf(Request $request)
    {
        $query = Boleto::with(['vehiculo.tipoVehiculo', 'espacio', 'metodoPago'])->where('esta_pagado', 1);
        if ($request->filled('fecha_inicio')) $query->whereDate('hora_salida', '>=', $request->fecha_inicio);
        if ($request->filled('fecha_fin')) $query->whereDate('hora_salida', '<=', $request->fecha_fin);

        $boletos = $query->orderByDesc('hora_salida')->get();
        $total = $boletos->sum('monto_total');

        $pdf = Pdf::loadView('reportes.pdf', compact('boletos', 'total'));
        return $pdf->download('reporte_estacionamiento_' . now()->format('Ymd_His') . '.pdf');
    }

    // Exportación a Excel (.xls/.csv compatible con Excel)
    public function exportarExcel(Request $request)
    {
        $query = Boleto::with(['vehiculo.tipoVehiculo', 'espacio', 'metodoPago'])->where('esta_pagado', 1);
        if ($request->filled('fecha_inicio')) $query->whereDate('hora_salida', '>=', $request->fecha_inicio);
        if ($request->filled('fecha_fin')) $query->whereDate('hora_salida', '<=', $request->fecha_fin);

        $boletos = $query->orderByDesc('hora_salida')->get();
        $filename = 'reporte_estacionamiento_' . now()->format('Ymd_His') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"', // <-- Corregido aquí
        ];

        $callback = function () use ($boletos) {
            echo "<table border='1'>";
            echo "<tr><th>Ticket</th><th>Placa</th><th>Tipo</th><th>Celda</th><th>Entrada</th><th>Salida</th><th>Minutos</th><th>Método Pago</th><th>Monto</th></tr>";
            foreach ($boletos as $b) {
                echo "<tr>";
                echo "<td>{$b->codigo_ticket}</td>";
                echo "<td>{$b->vehiculo->placa}</td>";
                echo "<td>{$b->vehiculo->tipoVehiculo->nombre}</td>";
                echo "<td>{$b->espacio->piso} - {$b->espacio->numero}</td>";
                echo "<td>{$b->hora_entrada}</td>";
                echo "<td>{$b->hora_salida}</td>";
                echo "<td>{$b->total_tiempo_minutos}</td>";
                echo "<td>" . ($b->metodoPago->nombre ?? '-') . "</td>";
                echo "<td>{$b->monto_total}</td>";
                echo "</tr>";
            }
            echo "</table>";
        };

        return response()->stream($callback, 200, $headers);
    }
}