<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->string('correo_electronico', 150)->unique();
            $table->string('contrasena');
            $table->foreignId('id_rol')->constrained('roles');
            $table->boolean('estado')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('telefono', 30);
            $table->string('correo_electronico', 150)->nullable();
            $table->boolean('es_suscriptor')->default(false);
            $table->string('convenio', 150)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('tipos_vehiculo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->decimal('tarifa_hora', 10, 2)->default(10.00);
            $table->decimal('tarifa_minuto', 10, 4)->default(0.2000);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 20)->unique();
            $table->foreignId('id_cliente')->nullable()->constrained('clientes');
            $table->foreignId('id_tipo_vehiculo')->constrained('tipos_vehiculo');
            $table->string('marca', 80);
            $table->string('modelo', 80);
            $table->string('color', 50);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('espacios_estacionamiento', function (Blueprint $table) {
            $table->id();
            $table->string('piso', 30);
            $table->string('sector', 30);
            $table->string('numero', 20);
            $table->boolean('esta_ocupado')->default(false);
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->unique(['piso', 'sector', 'numero']);
        });

        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_ticket', 30)->unique();
            $table->foreignId('id_vehiculo')->constrained('vehiculos');
            $table->foreignId('id_espacio_estacionamiento')->constrained('espacios_estacionamiento');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->dateTime('hora_entrada');
            $table->dateTime('hora_salida')->nullable();
            $table->integer('total_tiempo_minutos')->nullable();
            $table->foreignId('id_metodo_pago')->nullable()->constrained('metodos_pago');
            $table->decimal('monto_total', 10, 2)->default(0);
            $table->boolean('esta_pagado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletos');
        Schema::dropIfExists('metodos_pago');
        Schema::dropIfExists('espacios_estacionamiento');
        Schema::dropIfExists('vehiculos');
        Schema::dropIfExists('tipos_vehiculo');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles');
    }
};
