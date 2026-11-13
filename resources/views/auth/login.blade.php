<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar Sesión - ParkSystem</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1">Sistema de<b> Parqueo</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Inicie sesión para gestionar el estacionamiento</p>
      @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif
      <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="correo_electronico" class="form-control" placeholder="Correo electrónico" value="{{ old('correo_electronico') }}" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
          <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
