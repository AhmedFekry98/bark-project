<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Entities\User;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'provider_id',
        'price',
        'estimated_time',
        'addational_notes',
    ];

    public static $statuses = [
        'accepted',
        'rejected',
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\OfferFactory::new();
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
