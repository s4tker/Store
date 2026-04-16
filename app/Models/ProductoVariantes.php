<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
