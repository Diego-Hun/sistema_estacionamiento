@extends('layouts.admin')
@section('title', 'Clientes y Abonados')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h3 class="card-title">Gestión de Clientes / Abonados</h3>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCliente">+ Nuevo Cliente</button>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Suscriptor</th><th>Convenio / Vencimiento</th><th>Vehículos</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody>
        @foreach($clientes as $c)
        <tr>
          <td>{{ $c->nombre }}</td>
          <td>{{ $c->telefono }}</td>
          <td>{{ $c->correo_electronico }}</td>
          <td>{{ $c->es_suscriptor ? 'Sí (Mensual)' : 'Ocasional' }}</td>
          <td>{{ $c->convenio ?? '-' }} {{ $c->fecha_vencimiento ? '(' . $c->fecha_vencimiento . ')' : '' }}</td>
          <td>{{ $c->vehiculos->pluck('placa')->implode(', ') ?: 'Ninguno' }}</td>
          <td><span class="badge badge-{{ $c->estado ? 'success' : 'danger' }}">{{ $c->estado ? 'Activo' : 'Inactivo' }}</span></td>
          <td>
            <form action="{{ route('clientes.estado', $c) }}" method="POST">
              @csrf @method('PATCH')
              <button type="submit" class="btn btn-sm btn-{{ $c->estado ? 'warning' : 'success' }}">{{ $c->estado ? 'Desactivar' : 'Activar' }}</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="modalCliente">
  <div class="modal-dialog"><div class="modal-content">
    <form action="{{ route('clientes.store') }}" method="POST">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Registrar Cliente / Abonado</h5></div>
      <div class="modal-body">
        <div class="form-group"><label>Nombre Completo</label><input type="text" name="nombre" class="form-control" required></div>
        <div class="form-group"><label>Teléfono</label><input type="text" name="telefono" class="form-control" required></div>
        <div class="form-group"><label>Correo</label><input type="email" name="correo_electronico" class="form-control"></div>
        <div class="form-check mb-2"><input type="checkbox" name="es_suscriptor" class="form-check-input" id="sus"><label class="form-check-label" for="sus">Es Suscriptor / Mensualidad</label></div>
        <div class="form-group"><label>Convenio</label><input type="text" name="convenio" class="form-control"></div>
        <div class="form-group"><label>Vencimiento Mensualidad</label><input type="date" name="fecha_vencimiento" class="form-control"></div>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar</button></div>
    </form>
  </div></div>
</div>
<script>
    document.getElementById('miFormulario').addEventListener('submit', function(e) {
    
     e.preventDefault(); 
    });
</script>
@endsection
