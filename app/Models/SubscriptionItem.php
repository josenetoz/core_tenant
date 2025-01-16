<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'stripe_id',
        'stripe_product',
        'stripe_price',
        'quantity',
    ];

}
