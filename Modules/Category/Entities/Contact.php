<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'provider_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\ContactFactory::new();
    }
}
