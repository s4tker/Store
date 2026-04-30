<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoVariantes extends Model
{
    protected $table = 'ProductoVariantes';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'ProductoId',
        'Sku',
        'Precio',
        'PrecioOferta',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'ProductoId', 'Id');
    }
}
