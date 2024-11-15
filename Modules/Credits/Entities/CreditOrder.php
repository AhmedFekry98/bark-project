<?php

namespace Modules\Credits\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Entities\User;

class CreditOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'credits',
        'amount',
        'status'
    ];

    public static $statuses = [
        'pending',
        'paid',
        'canceled',
        'rejected',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Credits\Database\factories\CreditOrderFactory::new();
    }
}
