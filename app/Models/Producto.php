<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'Productos'; // Tu tabla real
    protected $primaryKey = 'Id';
    public $timestamps = false;

    // Relación con Marcas
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'MarcaId', 'Id');
    }

    // Accesor para la imagen (para que el index.blade.php no falle)
    public function getImageUrlAttribute()
    {
        // Esto busca la primera imagen del producto en tu tabla ProductoImagenes
        // Si no tienes imágenes aún, puedes devolver una por defecto
        return $this->imagenes()->first()?->Url ?? asset('img/default-product.png');
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagenes::class, 'ProductoId', 'Id')->orderBy('Orden', 'asc');
    }
}
