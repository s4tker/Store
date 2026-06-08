<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

// esta clase representa usuarios de la tienda
class Usuario extends Authenticatable
{
    protected $table = 'Usuarios';
    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Apellidos',
        'Correo',
        'Password',
        'Telefono',
        'Dni',
        'Ruc',
        'RazonSocial'
    ];

    protected $hidden = [
        'Password'
    ];
    // devuelve la contraseña para iniciar sesion

    public function getAuthPassword()
    {
        return $this->Password;
    }
    // conecta el usuario con sus direcciones

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'UsuarioId', 'Id');
    }
}
