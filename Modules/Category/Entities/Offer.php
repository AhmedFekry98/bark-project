<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'provider_id',
        'price',
        'estimated_time',
        'addational_notes',
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\OfferFactory::new();
    }
}
