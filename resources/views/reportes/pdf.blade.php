<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><style>body{font-family:sans-serif;font-size:12px;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #333;padding:5px;}</style></head>
<body>
  <h2>Reporte de Ingresos - Sistema de Estacionamiento</h2>
  <p><strong>Total Recaudado:</strong> Bs. {{ number_format($total, 2) }}</p>
  <table>
    <thead><tr><th>Ticket</th><th>Placa</th><th>Celda</th><th>Entrada</th><th>Salida</th><th>Pago</th><th>Monto</th></tr></thead>
    <tbody>
      @foreach($boletos as $b)
      <tr>
        <td>{{ $b->codigo_ticket }}</td>
        <td>{{ $b->vehiculo->placa }}</td>
        <td>{{ $b->espacio->piso }} - {{ $b->espacio->numero }}</td>
        <td>{{ $b->hora_entrada }}</td>
        <td>{{ $b->hora_salida }}</td>
        <td>{{ $b->metodoPago->nombre ?? '-' }}</td>
        <td>Bs. {{ number_format($b->monto_total, 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
