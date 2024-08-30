<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Entities\User;

class IgnoredRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'service_request_id'
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\IgnoredRequestFactory::new();
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }
}
