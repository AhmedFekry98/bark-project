<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CategoryQuestion extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "category_id",
        "question_text",
        "question_note",
        "type",
        "details",
    ];

    public static $types = [
        'texterea',
        'radios',
        'checkboxs',
        'options'
    ];

    protected $casts = [
        'details' => 'array'
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\CategoryQuestionFactory::new();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("option_image")
            ->singleFile()
            ->useFallbackUrl(fake()->imageUrl())
        ; // option_image
    }
}
