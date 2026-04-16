<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'Usuarios';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

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
            'UsuarioRoles',
            'UsuarioId',
            'RolId'
        );
    }

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class, 'UsuarioId', 'Id');
    }

    public function hasRole($roleName): bool
    {
        $expected = mb_strtolower((string) $roleName);

        return $this->roles->contains(function ($role) use ($expected) {
            return mb_strtolower((string) $role->Nombre) === $expected;
        });
    }

    public function getCreatedAtAttribute()
    {
        return $this->attributes['CreatedAt'] ?? null;
    }
}
