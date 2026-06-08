<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// esta clase representa productos dentro de un pedido
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
    // conecta el detalle con su pedido

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'PedidoId', 'Id');
    }
    // conecta el detalle con su variante

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariantes::class, 'VarianteId', 'Id');
    }
    // calcula el subtotal del detalle

    public function getSubtotalAttribute(): float
    {
        return (float) $this->Precio * (int) $this->Cantidad;
    }
}
