<?php

namespace Modules\Credits\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditPricingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'credits',
        'price',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Credits\Database\factories\CreditPricingPlanFactory::new();
    }
}
