@extends('layouts.admin')
@section('title', 'Vehículos')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h3 class="card-title">Gestión de Vehículos</h3>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalVehiculo">+ Registrar Vehículo</button>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>Placa</th><th>Tipo</th><th>Marca / Modelo</th><th>Color</th><th>Propietario</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody>
        @foreach($vehiculos as $v)
        <tr>
          <td><strong>{{ $v->placa }}</strong></td>
          <td>{{ $v->tipoVehiculo->nombre }}</td>
          <td>{{ $v->marca }} {{ $v->modelo }}</td>
          <td>{{ $v->color }}</td>
          <td>{{ $v->cliente->nombre ?? 'Particular / Ocasional' }}</td>
          <td><span class="badge badge-{{ $v->estado ? 'success' : 'danger' }}">{{ $v->estado ? 'Activo' : 'Inactivo' }}</span></td>
          <td>
            <form action="{{ route('vehiculos.estado', $v) }}" method="POST">
              @csrf @method('PATCH')
              <button class="btn btn-sm btn-{{ $v->estado ? 'warning' : 'success' }}">{{ $v->estado ? 'Desactivar' : 'Activar' }}</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="modalVehiculo">
  <div class="modal-dialog"><div class="modal-content">
    <form action="{{ route('vehiculos.store') }}" method="POST">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Registrar Vehículo</h5></div>
      <div class="modal-body">
        <div class="form-group"><label>Placa</label><input type="text" name="placa" class="form-control text-uppercase" required></div>
        <div class="form-group"><label>Tipo de Vehículo</label>
          <select name="id_tipo_vehiculo" class="form-control" required>
            @foreach($tipos as $t)<option value="{{ $t->id }}">{{ $t->nombre }} (Bs. {{ $t->tarifa_hora }}/h)</option>@endforeach
          </select>
        </div>
        <div class="form-group"><label>Marca</label><input type="text" name="marca" class="form-control" required></div>
        <div class="form-group"><label>Modelo</label><input type="text" name="modelo" class="form-control" required></div>
        <div class="form-group"><label>Color</label><input type="text" name="color" class="form-control" required></div>
        <div class="form-group"><label>Cliente Asociado (Opcional)</label>
          <select name="id_cliente" class="form-control">
            <option value="">-- Ninguno --</option>
            @foreach($clientes as $c)<option value="{{ $c->id }}">{{ $c->nombre }}</option>@endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary">Guardar</button></div>
    </form>
  </div></div>
</div>
@endsection
