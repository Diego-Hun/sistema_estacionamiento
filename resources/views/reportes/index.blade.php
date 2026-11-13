@extends('layouts.admin')
@section('title', 'Reportes y Estadísticas')
@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">Reportes Dinámicos (jQuery / AJAX)</h3></div>
  <div class="card-body">
    <form id="formFiltros" class="row mb-3">
      <div class="col-md-3"><label>Fecha Inicio</label><input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"></div>
      <div class="col-md-3"><label>Fecha Fin</label><input type="date" name="fecha_fin" id="fecha_fin" class="form-control"></div>
      <div class="col-md-3"><label>Placa</label><input type="text" name="placa" id="placa" class="form-control" placeholder="Filtrar placa..."></div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="button" id="btnFiltrar" class="btn btn-primary mr-2"><i class="fas fa-search"></i> Filtrar</button>
        <a id="btnPdf" href="{{ route('reportes.pdf') }}" class="btn btn-danger mr-1"><i class="fas fa-file-pdf"></i> PDF</a>
        <a id="btnExcel" href="{{ route('reportes.excel') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</a>
      </div>
    </form>

    <div class="alert alert-info">
      <strong>Total Recaudado en Filtro:</strong> Bs. <span id="lblTotal">0.00</span> |
      <strong>Ocupación Actual:</strong> <span id="lblOcupacion">0 / 0</span>
    </div>

    <table class="table table-bordered" id="tablaReportes">
      <thead><tr><th>Ticket</th><th>Placa</th><th>Celda</th><th>Entrada</th><th>Salida</th><th>Minutos</th><th>Pago</th><th>Monto</th></tr></thead>
      <tbody></tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
function cargarReporte() {
  let params = $('#formFiltros').serialize();
  $('#btnPdf').attr('href', "{{ route('reportes.pdf') }}?" + params);
  $('#btnExcel').attr('href', "{{ route('reportes.excel') }}?" + params);

  $.get("{{ route('reportes.ajax') }}?" + params, function (res) {
    $('#lblTotal').text(res.total_ingresos);
    $('#lblOcupacion').text(res.ocupacion.ocupados + ' / ' + res.ocupacion.totales);
    let rows = '';
    res.boletos.forEach(b => {
      rows += `<tr>
        <td>${b.codigo_ticket}</td>
        <td>${b.vehiculo.placa}</td>
        <td>${b.espacio.piso} - ${b.espacio.numero}</td>
        <td>${b.hora_entrada}</td>
        <td>${b.hora_salida}</td>
        <td>${b.total_tiempo_minutos}</td>
        <td>${b.metodo_pago ? b.metodo_pago.nombre : '-'}</td>
        <td>Bs. ${parseFloat(b.monto_total).toFixed(2)}</td>
      </tr>`;
    });
    $('#tablaReportes tbody').html(rows);
  });
}
$(document).ready(function () {
  cargarReporte();
  $('#btnFiltrar').click(cargarReporte);
});
</script>
@endpush
