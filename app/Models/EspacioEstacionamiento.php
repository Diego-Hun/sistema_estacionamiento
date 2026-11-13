<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EspacioEstacionamiento extends Model
{
    protected $table = 'espacios_estacionamiento';
    protected $fillable = ['piso', 'sector', 'numero', 'esta_ocupado', 'estado'];
}
