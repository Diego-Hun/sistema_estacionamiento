@extends('layouts.admin')
@section('title', 'Operativa y Caja')
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="card card-primary">
      <div class="card-header"><h3 class="card-title">Registrar Entrada</h3></div>
      <form action="{{ route('boletos.entrada') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group"><label>Placa del Vehículo</label><input type="text" name="placa" class="form-control text-uppercase" placeholder="ABC-123" required></div>
          <div class="form-group"><label>Tipo de Vehículo</label>
            <select name="id_tipo_vehiculo" class="form-control" required>
              @foreach($tiposVehiculo as $t)<option value="{{ $t->id }}">{{ $t->nombre }} (Bs. {{ $t->tarifa_hora }}/h)</option>@endforeach
            </select>
          </div>
          <div class="form-group"><label>Asignar Celda Disponible</label>
            <select name="id_espacio_estacionamiento" class="form-control" required>
              @foreach($espaciosDisponibles as $e)<option value="{{ $e->id }}">{{ $e->piso }} - {{ $e->sector }} - {{ $e->numero }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="card-footer"><button class="btn btn-primary btn-block"><i class="fas fa-print"></i> Emitir Ticket de Entrada</button></div>
      </form>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card card-warning">
      <div class="card-header"><h3 class="card-title">Vehículos en Estacionamiento (Pendientes de Salida / Cobro)</h3></div>
      <div class="card-body">
        <table class="table table-bordered datatable">
          <thead><tr><th>Ticket</th><th>Placa</th><th>Tipo</th><th>Celda</th><th>Hora Entrada</th><th>Acciones</th></tr></thead>
          <tbody>
            @foreach($boletosActivos as $b)
            <tr>
              <td>{{ $b->codigo_ticket }}</td>
              <td><strong>{{ $b->vehiculo->placa }}</strong></td>
              <td>{{ $b->vehiculo->tipoVehiculo->nombre }}</td>
              <td>{{ $b->espacio->piso }} - {{ $b->espacio->numero }}</td>
              <td>{{ $b->hora_entrada->format('d/m/Y H:i') }}</td>
              <td>
                <a href="{{ route('boletos.ticket', $b) }}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-receipt"></i> Ticket</a>
                <button class="btn btn-sm btn-success btn-cobrar" data-id="{{ $b->id }}"><i class="fas fa-cash-register"></i> Salida y Cobro</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCobro">
  <div class="modal-dialog"><div class="modal-content">
    <form action="{{ route('boletos.salida') }}" method="POST">
      @csrf
      <input type="hidden" name="id_boleto" id="cobro_id_boleto">
      <div class="modal-header bg-success text-white"><h5 class="modal-title">Caja - Liquidación y Salida</h5></div>
      <div class="modal-body">
        <p><strong>Ticket:</strong> <span id="lbl_ticket"></span> | <strong>Placa:</strong> <span id="lbl_placa"></span></p>
        <p><strong>Entrada:</strong> <span id="lbl_entrada"></span> | <strong>Tiempo:</strong> <span id="lbl_minutos"></span> min.</p>
        <div id="alerta_abonado" class="alert alert-info d-none">Vehículo con suscripción mensual vigente (Tarifa Bs. 0.00).</div>
        <div class="form-group"><label>Total a Cobrar (Bs.)</label><input type="number" step="0.01" name="monto_total" id="cobro_monto" class="form-control font-weight-bold" required></div>
        <div class="form-group"><label>Método de Pago</label>
          <select name="id_metodo_pago" class="form-control" required>
            @foreach($metodosPago as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-success btn-block">Confirmar Pago y Liberar Celda</button></div>
    </form>
  </div></div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.btn-cobrar', function () {
  let id = $(this).data('id');
  $.get("{{ route('boletos.calcular') }}", { id_boleto: id }, function (res) {
    $('#cobro_id_boleto').val(id);
    $('#lbl_ticket').text(res.codigo_ticket);
    $('#lbl_placa').text(res.placa);
    $('#lbl_entrada').text(res.hora_entrada);
    $('#lbl_minutos').text(res.minutos);
    $('#cobro_monto').val(res.monto_total);
    if (res.es_abonado) $('#alerta_abonado').removeClass('d-none');
    else $('#alerta_abonado').addClass('d-none');
    $('#modalCobro').modal('show');
  });
});
</script>
@endpush
