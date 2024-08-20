<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abilities',
    ];

    protected $casts = [
        'abilities' => 'array'
    ];

    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\RoleFactory::new();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
