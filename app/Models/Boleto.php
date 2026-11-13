<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    protected $table = 'boletos';
    protected $fillable = [
        'codigo_ticket', 'id_vehiculo', 'id_espacio_estacionamiento', 'id_usuario',
        'hora_entrada', 'hora_salida', 'total_tiempo_minutos', 'id_metodo_pago',
        'monto_total', 'esta_pagado'
    ];
    protected $casts = ['hora_entrada' => 'datetime', 'hora_salida' => 'datetime'];

    public function vehiculo() { return $this->belongsTo(Vehiculo::class, 'id_vehiculo'); }
    public function espacio() { return $this->belongsTo(EspacioEstacionamiento::class, 'id_espacio_estacionamiento'); }
    public function usuario() { return $this->belongsTo(Usuario::class, 'id_usuario'); }
    public function metodoPago() { return $this->belongsTo(MetodoPago::class, 'id_metodo_pago'); }
}
