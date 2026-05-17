<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
