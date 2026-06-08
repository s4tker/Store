<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// esta clase representa imagenes de producto
class ProductoImagenes extends Model
{
    protected $table = 'ProductoImagenes';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'ProductoId',
        'Url',
        'Orden',
    ];
}
