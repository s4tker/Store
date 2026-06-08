<?php

// este modelo representa datos de la tienda
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// esta clase representa codigos pendientes
class PendingUserVerification extends Model
{
    protected $table = 'PendingUserVerifications';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Email',
        'Password',
        'OtpCode',
        'ExpiresAt',
        'CreatedAt',
    ];

    protected $casts = [
        'ExpiresAt' => 'datetime',
        'CreatedAt' => 'datetime',
    ];
}
