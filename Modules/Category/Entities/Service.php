<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Auth\Entities\Profession;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'profession_id',
        'credits',
        'is_offline',
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\ServiceFactory::new();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl(fake()->imageUrl());
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
