<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// esta clase representa stock disponible
class Inventario extends Model
{
    protected $table = 'Inventario';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'VarianteId',
        'Stock',
    ];
    // conecta el detalle con su variante

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariantes::class, 'VarianteId', 'Id');
    }
}
