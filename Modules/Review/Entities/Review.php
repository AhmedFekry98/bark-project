<?php

namespace Modules\Review\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Auth\Entities\User;
use Modules\Category\Entities\ServiceRequest;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'reviewable_id',
        'request_id',
        'comment',
        'stars',
    ];


    // protected static function newFactory()
    // {
    //     return \Modules\Review\Database\factories\ReviewFactory::new();
    // }


    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewable_id');
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
