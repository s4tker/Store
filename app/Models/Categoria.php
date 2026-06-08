<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// esta clase representa categorias del catalogo
class Categoria extends Model
{
    protected $table = 'Categorias';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Slug', 'ParentId'];
    // conecta categorias hijas

    public function subcategorias()
    {
        return $this->hasMany(self::class, 'ParentId', 'Id');
    }
    // conecta una categoria con su padre

    public function padre()
    {
        return $this->belongsTo(self::class, 'ParentId', 'Id');
    }
}
