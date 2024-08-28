<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profession extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\ProfessionsFactory::new();
    }


    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
