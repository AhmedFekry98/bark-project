<?php

namespace Modules\Auth\Entities;

use App\Traits\WithRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\Service;
use Modules\Category\Entities\ServiceRequest;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, HasApiTokens, SoftDeletes, WithRoles, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'service_id',
        'city_id',
        'service_data',
        'company_name',
        'company_website',
        'company_size',
        'sales',
        'social',
        // 'extra',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password'    => 'hashed',
        'verified_at' => 'datetime',
        'service_data'  => 'array',
        // 'extra'       => 'array',
    ];

    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\UserFactory::new();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('user')
            ->singleFile()
            ->useFallbackUrl(fake()->imageUrl());
    }


    public function  getIsVerifiedAttribute(): bool
    {
        return $this->verified_at
            ? true
            :  false;
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceRequestsSent(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'user_id');
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'hired_id');
    }
}
