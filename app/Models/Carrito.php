<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// esta clase representa el carrito del usuario
class Carrito extends Model
{
    protected $table = 'Carritos';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'UsuarioId',
    ];
    // conecta el pedido con su usuario

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UsuarioId', 'Id');
    }
    // conecta el carrito con sus productos

    public function items(): HasMany
    {
        return $this->hasMany(CarritoItem::class, 'CarritoId', 'Id');
    }
}
