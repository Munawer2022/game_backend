<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCoin extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'available_coins',
        'purchase_coins',
        'lose_coins',
        'won_coins',
        'withdraw_coins',
        
    ];
}
