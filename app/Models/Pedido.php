<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// esta clase representa pedidos de compra
class Pedido extends Model
{
    protected $table = 'Pedidos';
    protected $primaryKey = 'Id';
    public $timestamps = true;
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = null;

    protected $fillable = [
        'UsuarioId',
        'DireccionId',
        'Total',
        'Estado',
    ];

    protected $casts = [
        'CreatedAt' => 'datetime',
    ];
    // guarda el estado del pedido limpio

    public function setEstadoAttribute(?string $value): void
    {
        $this->attributes['Estado'] = mb_strtolower(trim((string) ($value ?: 'pendiente')));
    }
    // limpia el estado del pedido

    public function getEstadoNormalizadoAttribute(): string
    {
        return mb_strtolower(trim((string) $this->Estado));
    }
    // muestra el estado del pedido

    public function getEstadoTextoAttribute(): string
    {
        return ucfirst($this->estado_normalizado ?: 'pendiente');
    }
    // conecta el pedido con su usuario

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UsuarioId', 'Id');
    }
    // conecta el pedido con su direccion

    public function direccion(): BelongsTo
    {
        return $this->belongsTo(Direccion::class, 'DireccionId', 'Id');
    }
    // conecta el pedido con sus detalles

    public function detalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class, 'PedidoId', 'Id');
    }
}
