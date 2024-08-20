<?php

namespace Modules\Auth\Entities;

use App\Traits\WithRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Restaurants\Entities\Restaurant;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, HasApiTokens, SoftDeletes, WithRoles, InteractsWithMedia;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'extra',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password'    => 'hashed',
        'verified_at' => 'datetime',
        'extra'       => 'array'
    ];

    public function  getIsVerifiedAttribute(): bool
    {
        return $this->verified_at
            ? true
            :  false;
    }

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


    protected function restaurant(): HasOne
    {
        return $this->hasOne(Restaurant::class, 'owner_id');
    }
}
