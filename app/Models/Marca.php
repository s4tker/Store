<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// esta clase representa marcas de producto
class Marca extends Model
{
    protected $table = 'Marcas';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Slug'];
}
