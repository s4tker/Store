<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'Productos';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = [
        'Nombre',
        'Slug',
        'Descripcion',
        'CategoriaId',
        'MarcaId',
        'Estado',
    ];
    protected $appends = ['image_url', 'display_price'];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'MarcaId', 'Id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'CategoriaId', 'Id');
    }

    public function variantes()
    {
        return $this->hasMany(ProductoVariantes::class, 'ProductoId', 'Id');
    }

    public function getImageUrlAttribute()
    {
        $primeraImagen = $this->relationLoaded('imagenes')
            ? $this->imagenes->first()
            : $this->imagenes()->first();

        if (! $primeraImagen) {
            return asset('img/logo/logo.png');
        }

        if (str_starts_with($primeraImagen->Url, 'http')) {
            return $primeraImagen->Url;
        }

        return asset('storage/' . ltrim($primeraImagen->Url, '/'));
    }

    public function imagenes()
    {
        return $this->hasMany(ProductoImagenes::class, 'ProductoId', 'Id')->orderBy('Orden', 'asc');
    }

    public function getDisplayPriceAttribute(): float
    {
        $variante = $this->relationLoaded('variantes')
            ? $this->variantes->sortBy('Id')->first()
            : $this->variantes()->orderBy('Id')->first();

        if (! $variante) {
            return 0;
        }

        return (float) ($variante->PrecioOferta ?: $variante->Precio ?: 0);
    }
}
