<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoDetalle extends Model
{
    protected $table = 'PedidoDetalles';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'PedidoId',
        'VarianteId',
        'Cantidad',
        'Precio',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'PedidoId', 'Id');
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariantes::class, 'VarianteId', 'Id');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->Precio * (int) $this->Cantidad;
    }
}
