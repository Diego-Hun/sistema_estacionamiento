<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>Ticket {{ $boleto->codigo_ticket }}</title></head>
<body onload="window.print()" style="font-family: monospace; width: 280px; margin: auto;">
  <h3 style="text-align:center;">PARKSYSTEM - TICKET</h3>
  <hr>
  <p><strong>Ticket:</strong> {{ $boleto->codigo_ticket }}</p>
  <p><strong>Placa:</strong> {{ $boleto->vehiculo->placa }}</p>
  <p><strong>Tipo:</strong> {{ $boleto->vehiculo->tipoVehiculo->nombre }}</p>
  <p><strong>Celda:</strong> {{ $boleto->espacio->piso }} / {{ $boleto->espacio->sector }} / {{ $boleto->espacio->numero }}</p>
  <p><strong>Entrada:</strong> {{ $boleto->hora_entrada->format('d/m/Y H:i:s') }}</p>
  @if($boleto->hora_salida)
    <p><strong>Salida:</strong> {{ $boleto->hora_salida->format('d/m/Y H:i:s') }}</p>
    <p><strong>Total Pagado:</strong> Bs. {{ number_format($boleto->monto_total, 2) }} ({{ $boleto->metodoPago->nombre ?? '' }})</p>
  @endif
  <hr>
  <p style="text-align:center;">Conserve este comprobante</p>
</body>
</html>
