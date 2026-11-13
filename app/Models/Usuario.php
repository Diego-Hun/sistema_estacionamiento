<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $fillable = ['nombre', 'correo_electronico', 'contrasena', 'id_rol', 'estado'];
    protected $hidden = ['contrasena', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
