<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'question_note',
        'type',
        'options',
    ];

    public static $types = [
        'texterea',
        'checkbox',
        'radio'
    ];

    protected $casts = [
        'options'  => 'array'
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\QuestionFactory::new();
    }
}
