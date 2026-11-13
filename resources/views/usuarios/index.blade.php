@extends('layouts.admin')
@section('title', 'Usuarios y Roles')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h3 class="card-title">Gestión de Usuarios y Roles</h3>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalUsuario">+ Nuevo Usuario</button>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped datatable">
      <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody>
        @foreach($usuarios as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->nombre }}</td>
          <td>{{ $u->correo_electronico }}</td>
          <td><span class="badge badge-primary">{{ $u->rol->nombre }}</span></td>
          <td><span class="badge badge-{{ $u->estado ? 'success' : 'danger' }}">{{ $u->estado ? 'Activo' : 'Inactivo' }}</span></td>
          <td>
            <form action="{{ route('usuarios.estado', $u) }}" method="POST" class="d-inline">
              @csrf @method('PATCH')
              <button class="btn btn-sm btn-{{ $u->estado ? 'warning' : 'success' }}">{{ $u->estado ? 'Desactivar' : 'Activar' }}</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="modalUsuario">
  <div class="modal-dialog"><div class="modal-content">
    <form action="{{ route('usuarios.store') }}" method="POST">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Registrar Usuario</h5></div>
      <div class="modal-body">
        <div class="form-group"><label>Nombre</label><input type="text" name="nombre" class="form-control" required></div>
        <div class="form-group"><label>Correo Electrónico</label><input type="email" name="correo_electronico" class="form-control" required></div>
        <div class="form-group"><label>Contraseña</label><input type="password" name="contrasena" class="form-control" required></div>
        <div class="form-group"><label>Rol</label>
          <select name="id_rol" class="form-control" required>
            @foreach($roles as $r)<option value="{{ $r->id }}">{{ $r->nombre }}</option>@endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-primary">Guardar</button></div>
    </form>
  </div></div>
</div>
@endsection
