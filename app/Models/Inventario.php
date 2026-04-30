<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'Inventario';
    protected $primaryKey = 'VarianteId';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'VarianteId',
        'Stock',
    ];

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariantes::class, 'VarianteId', 'Id');
    }
}
