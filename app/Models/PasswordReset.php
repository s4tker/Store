<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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