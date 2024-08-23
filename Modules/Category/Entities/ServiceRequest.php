<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'hired_id',
        'questions_data'
    ];

    protected $casts = [
        'questions_data' => 'array'
    ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\ServiceRequestFactory::new();
    }
}
