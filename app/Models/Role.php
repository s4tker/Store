<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// esta clase representa roles de usuario
class Role extends Model
{
    protected $table = 'Roles';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = ['Nombre'];
}
