<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\EspacioController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\ReporteController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Solo Administrador: Usuarios y Roles
    Route::middleware(['rol:Administrador'])->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{usuario}/estado', [UsuarioController::class, 'toggleEstado'])->name('usuarios.estado');
    });

    // Administrador y Supervisor: Configuración de Espacios y Reportes
    Route::middleware(['rol:Administrador,Supervisor'])->group(function () {
        Route::get('/espacios', [EspacioController::class, 'index'])->name('espacios.index');
        Route::post('/espacios', [EspacioController::class, 'store'])->name('espacios.store');
        Route::put('/espacios/{espacio}', [EspacioController::class, 'update'])->name('espacios.update');
        Route::patch('/espacios/{espacio}/estado', [EspacioController::class, 'toggleEstado'])->name('espacios.estado');

        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/ajax', [ReporteController::class, 'filtrarAjax'])->name('reportes.ajax');
        Route::get('/reportes/pdf', [ReporteController::class, 'exportarPdf'])->name('reportes.pdf');
        Route::get('/reportes/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.excel');
    });

    // Todos los roles autenticados: Clientes, Vehículos, Entradas/Salidas y Caja
    Route::middleware(['rol:Administrador,Operador,Supervisor'])->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::patch('/clientes/{cliente}/estado', [ClienteController::class, 'toggleEstado'])->name('clientes.estado');

        Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');
        Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
        Route::put('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->name('vehiculos.update');
        Route::patch('/vehiculos/{vehiculo}/estado', [VehiculoController::class, 'toggleEstado'])->name('vehiculos.estado');

        Route::get('/operativa', [BoletoController::class, 'index'])->name('boletos.index');
        Route::post('/operativa/entrada', [BoletoController::class, 'registrarEntrada'])->name('boletos.entrada');
        Route::get('/operativa/calcular-tarifa', [BoletoController::class, 'calcularTarifa'])->name('boletos.calcular');
        Route::post('/operativa/salida-cobro', [BoletoController::class, 'registrarSalidaCobro'])->name('boletos.salida');
        Route::get('/operativa/ticket/{boleto}', [BoletoController::class, 'ticketDigital'])->name('boletos.ticket');
    });
});
