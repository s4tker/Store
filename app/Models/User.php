<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

// esta clase representa usuarios de la tienda
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'Usuarios';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

    protected $fillable = [
        'Alias', 'Nombre', 'Apellidos', 'Correo', 'Password',
        'Telefono', 'Dni', 'Ruc', 'RazonSocial'
    ];

    protected $hidden = [
        'Password',
    ];
    // devuelve la contraseña para iniciar sesion

    public function getAuthPassword()
    {
        return $this->Password;
    }
    // conecta el usuario con sus roles

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'UsuarioRoles',
            'UsuarioId',
            'RolId'
        );
    }
    // conecta el usuario con sus direcciones

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class, 'UsuarioId', 'Id');
    }
    // conecta el usuario con sus pedidos

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'UsuarioId', 'Id');
    }
    // revisa si el usuario tiene un rol

    public function hasRole($roleName): bool
    {
        $expected = mb_strtolower((string) $roleName);

        return $this->roles->contains(function ($role) use ($expected) {
            return mb_strtolower((string) $role->Nombre) === $expected;
        });
    }
    // devuelve la fecha de creacion

    public function getCreatedAtAttribute()
    {
        return $this->attributes['CreatedAt'] ?? null;
    }
}
