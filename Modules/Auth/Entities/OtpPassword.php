<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtpPassword extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'token',
        'expire_at'
    ];
    
    protected static function newFactory()
    {
        // return \Modules\Auth\Database\factories\OtpPasswordFactory::new();
    }
}
