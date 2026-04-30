<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    protected $table = 'Carritos';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'UsuarioId',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'UsuarioId', 'Id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CarritoItem::class, 'CarritoId', 'Id');
    }
}
