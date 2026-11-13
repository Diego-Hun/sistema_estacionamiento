@extends('layouts.admin')
@section('title', 'Panel Principal')
@section('content')
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner"><h3>{{ $disponibles }} / {{ $totalEspacios }}</h3><p>Celdas Disponibles</p></div>
      <div class="icon"><i class="fas fa-parking"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner"><h3>{{ $vehiculosActivos }}</h3><p>Vehículos Estacionados</p></div>
      <div class="icon"><i class="fas fa-car"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner"><h3>Bs. {{ number_format($ingresosHoy, 2) }}</h3><p>Ingresos de Hoy</p></div>
      <div class="icon"><i class="fas fa-cash-register"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner"><h3>{{ $abonados }}</h3><p>Clientes Abonados</p></div>
      <div class="icon"><i class="fas fa-id-card"></i></div>
    </div>
  </div>
</div>
@endsection
