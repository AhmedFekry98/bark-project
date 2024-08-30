<?php

namespace Modules\Auth\Entities;

use App\Traits\WithRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Category\Entities\Category;
use Modules\Category\Entities\IgnoredRequest;
use Modules\Category\Entities\Service;
use Modules\Category\Entities\ServiceRequest;
use Modules\World\Entities\City;
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

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function ignoredRequests(): HasMany
    {
        return $this->hasMany(IgnoredRequest::class, 'provider_id');
    }

    public function serviceRequestsSent(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'user_id');
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'hired_id');
    }

    public function professions(): BelongsToMany
    {
        return $this->belongsToMany(Profession::class);
    }
}
