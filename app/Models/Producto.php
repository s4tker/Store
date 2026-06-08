<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// esta clase representa productos del catalogo
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
    // conecta el producto con su marca

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'MarcaId', 'Id');
    }
    // conecta el producto con su categoria

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'CategoriaId', 'Id');
    }
    // conecta el producto con sus variantes

    public function variantes()
    {
        return $this->hasMany(ProductoVariantes::class, 'ProductoId', 'Id');
    }
    // arma la ruta de la imagen

    public function getImageUrlAttribute()
    {
        $primeraImagen = $this->relationLoaded('imagenes')
            ? $this->imagenes->first()
            : $this->imagenes()->first();

        return self::resolveImageUrl($primeraImagen?->Url);
    }

    public static function resolveImageUrl(?string $path): string
    {
        $path = ltrim((string) $path, '/');

        if ($path === '') {
            return asset('img/logo/logo.png');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        if (file_exists(storage_path('app/public/' . $path))) {
            return asset('storage/' . $path);
        }

        return asset('img/logo/logo.png');
    }
    // conecta el producto con sus imagenes

    public function imagenes()
    {
        return $this->hasMany(ProductoImagenes::class, 'ProductoId', 'Id')->orderBy('Orden', 'asc');
    }
    // calcula el precio visible

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
