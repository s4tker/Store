<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// esta clase representa recuperaciones de clave
class PasswordReset extends Model
{
    protected $table = 'PasswordResets';
    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'Correo',
        'Token'
    ];
}