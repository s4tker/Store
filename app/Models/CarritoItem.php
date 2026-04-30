<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class, 'CarritoId', 'Id');
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
