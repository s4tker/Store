<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// esta clase representa productos del carrito
class CarritoItem extends Model
{
    protected $table = 'CarritoItems';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'CarritoId',
        'VarianteId',
        'Cantidad',
        'Precio',
    ];
    // conecta el item con su carrito

    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class, 'CarritoId', 'Id');
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
