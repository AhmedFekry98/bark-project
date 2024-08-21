<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Entities\User;

class CategoryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        "category_id",
        "customer_id",
        "questions_data",
    ];

    protected $casts = [
        'questions_data'  => 'array'
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\CategoryRequestFactory::new();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
