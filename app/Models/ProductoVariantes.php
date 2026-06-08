<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// esta clase representa variantes de producto
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
    // conecta la variante con su producto

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'ProductoId', 'Id');
    }
}
