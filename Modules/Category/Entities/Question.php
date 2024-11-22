<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'question_note',
        'type',
    ];

    public static $types = [
        'texterea',
        'checkbox',
        'radio'
    ];

    protected $casts = [
        // 
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\QuestionFactory::new();
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }
}
