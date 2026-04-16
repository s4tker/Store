<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function getAuthPassword()
    {
        return $this->Password;
    }

    // 🔑 Relación clave para tu módulo
    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'UsuarioId', 'Id');
    }
}
