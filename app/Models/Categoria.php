<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'Categorias';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Slug', 'ParentId'];

    public function subcategorias()
    {
        return $this->hasMany(self::class, 'ParentId', 'Id');
    }

    public function padre()
    {
        return $this->belongsTo(self::class, 'ParentId', 'Id');
    }
}
