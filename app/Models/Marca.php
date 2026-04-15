<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'Marcas';
    protected $primaryKey = 'Id';
    public $timestamps = false;
}
