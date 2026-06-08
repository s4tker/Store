<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// esta clase representa direcciones de entrega
class Direccion extends Model
{
    protected $table = 'Direcciones';
    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'UsuarioId',
        'Pais',
        'Region',
        'Ciudad',
        'Direccion',
        'Referencia'
    ];
    // conecta el pedido con su usuario

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'UsuarioId', 'Id');
    }
}