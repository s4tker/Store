<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoStock extends Model
{
    protected $table = 'MovimientosStock';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'VarianteId',
        'Tipo',
        'Cantidad',
        'Motivo',
    ];

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariantes::class, 'VarianteId', 'Id');
    }
}
