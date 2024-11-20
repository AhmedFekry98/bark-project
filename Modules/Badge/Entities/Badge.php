<?php

namespace Modules\Badge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Badge extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'label'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')->singleFile();
    }
    
    protected static function newFactory()
    {
        return \Modules\Badge\Database\factories\BadgeFactory::new();
    }
}
