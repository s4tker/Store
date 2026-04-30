<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UsuarioId', 'Id');
    }

    public function direccion(): BelongsTo
    {
        return $this->belongsTo(Direccion::class, 'DireccionId', 'Id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(PedidoDetalle::class, 'PedidoId', 'Id');
    }
}
