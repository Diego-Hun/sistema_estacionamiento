@extends('layouts.admin')
@section('title', 'Celdas y Espacios')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h3 class="card-title">Configuración de Celdas / Espacios</h3>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalEspacio">+ Nueva Celda</button>
  </div>
  <div class="card-body">
    <div class="row mb-4">
      @foreach($espacios as $e)
        <div class="col-md-2 col-4 mb-2">
          <div class="p-2 text-center rounded text-white {{ !$e->estado ? 'bg-secondary' : ($e->esta_ocupado ? 'bg-danger' : 'bg-success') }}">
            <strong>{{ $e->numero }}</strong><br><small>{{ $e->piso }} - {{ $e->sector }}</small><br>
            <span class="badge badge-light">{{ !$e->estado ? 'Inactivo' : ($e->esta_ocupado ? 'Ocupado' : 'Libre') }}</span>
          </div>
        </div>
      @endforeach
    </div>

    <table class="table table-bordered datatable">
      <thead><tr><th>Piso</th><th>Sector</th><th>Número</th><th>Ocupación</th><th>Estado</th><th>Acción</th></tr></thead>
      <tbody>
        @foreach($espacios as $e)
        <tr>
          <td>{{ $e->piso }}</td>
          <td>{{ $e->sector }}</td>
          <td>{{ $e->numero }}</td>
          <td><span class="badge badge-{{ $e->esta_ocupado ? 'danger' : 'success' }}">{{ $e->esta_ocupado ? 'Ocupada' : 'Disponible' }}</span></td>
          <td><span class="badge badge-{{ $e->estado ? 'primary' : 'secondary' }}">{{ $e->estado ? 'Activo' : 'Desactivado' }}</span></td>
          <td>
            <form action="{{ route('espacios.estado', $e) }}" method="POST">
              @csrf @method('PATCH')
              <button class="btn btn-sm btn-{{ $e->estado ? 'warning' : 'success' }}">{{ $e->estado ? 'Desactivar' : 'Activar' }}</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="modalEspacio">
  <div class="modal-dialog"><div class="modal-content">
    <form action="{{ route('espacios.store') }}" method="POST">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Agregar Espacio</h5></div>
      <div class="modal-body">
        <div class="form-group"><label>Piso / Nivel</label><input type="text" name="piso" class="form-control" placeholder="Piso 1" required></div>
        <div class="form-group"><label>Sector</label><input type="text" name="sector" class="form-control" placeholder="Sector A" required></div>
        <div class="form-group"><label>Número de Celda</label><input type="text" name="numero" class="form-control" placeholder="C-11" required></div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary">Guardar</button></div>
    </form>
  </div></div>
</div>
@endsection
