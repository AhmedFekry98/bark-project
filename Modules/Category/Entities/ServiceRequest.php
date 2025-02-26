<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Auth\Entities\User;
use Modules\World\Entities\City;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'city_id',
        'user_id',
        'questions_data',
        'total_credits',
        'is_approved',
        'status'
    ];

    protected $casts = [
        'questions_data' => 'array',

    ];

    public static $statuses = [
        'pending',
        'rejected',
        'hired',
        'archive'
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\ServiceRequestFactory::new();
    }

    public function getUrlAttribute(): string
    {
        return str_replace('{id}', $this->id, config('settings.request_page_url', asset('api/categories/services/requests/{id}')));
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hired_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class);
    }

    public function acceptedEstimate(): HasOne
    {
        return $this->hasOne(Estimate::class)
            ->whereStatus('accepted');
    }
}
