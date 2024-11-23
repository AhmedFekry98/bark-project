<?php

namespace Modules\Badge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Entities\User;

class ProviderBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'badge_id',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Badge\Database\factories\ProviderBadgeFactory::new();
    }
}
