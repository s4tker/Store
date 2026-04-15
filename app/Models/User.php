<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'Usuarios';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre', 'Apellidos', 'Correo', 'Password',
        'Telefono', 'Dni', 'Ruc', 'RazonSocial'
    ];

    protected $hidden = [
        'Password',
    ];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    /**
     * Relación con los roles.
     * UsuarioRoles es la tabla pivote.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'UsuarioRoles', // Tabla intermedia
            'UsuarioId',    // FK en tabla intermedia para este modelo
            'RolId'         // FK en tabla intermedia para el modelo Role
        );
    }

    /**
     * Función para verificar roles en el Blade.
     */
    public function hasRole($roleName): bool
    {
        return $this->roles->contains('Nombre', $roleName);
    }
}
