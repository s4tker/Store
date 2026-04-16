<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'UsuarioId', 'Id');
    }
}