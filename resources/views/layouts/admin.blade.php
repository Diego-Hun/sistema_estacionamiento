<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Sistema de Estacionamiento')</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item mr-3 d-flex align-items-center">
        <span class="badge badge-info">{{ Auth::user()->rol->nombre ?? '' }}</span>&nbsp;
        <strong>{{ Auth::user()->nombre }}</strong>
      </li>
      <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Salir</button>
        </form>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
      <i class="fas fa-parking brand-image img-circle elevation-3 ml-3 mt-1 text-warning"></i>
      <span class="brand-text font-weight-light">Parqueo Lucas</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link"><i ></i><p>Panel Principal</p></a></li>
          <li class="nav-item"><a href="{{ route('boletos.index') }}" class="nav-link"><i></i><p>Entradas, Salidas y Caja</p></a></li>
          <li class="nav-item"><a href="{{ route('vehiculos.index') }}" class="nav-link"><i></i><p>Vehículos</p></a></li>
          <li class="nav-item"><a href="{{ route('clientes.index') }}" class="nav-link"><i></i><p>Clientes / Abonados</p></a></li>
          @if(in_array(Auth::user()->rol->nombre ?? '', ['Administrador', 'Supervisor']))
          <li class="nav-item"><a href="{{ route('espacios.index') }}" class="nav-link"><i></i><p>Celdas / Espacios</p></a></li>
          <li class="nav-item"><a href="{{ route('reportes.index') }}" class="nav-link"><i></i><p>Reportes y Estadísticas</p></a></li>
          @endif
          @if((Auth::user()->rol->nombre ?? '') === 'Administrador')
          <li class="nav-item"><a href="{{ route('usuarios.index') }}" class="nav-link"><i></i><p>Usuarios y Roles</p></a></li>
          @endif
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper p-3">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    @yield('content')
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(function () {
    $('.datatable').DataTable({
      language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' }
    });
  });
</script>
@stack('scripts')
</body>
</html>
