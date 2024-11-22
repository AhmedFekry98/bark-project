<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'value',
        'increment_credits',
    ];

    
    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\QuestionOptionFactory::new();
    }
}
