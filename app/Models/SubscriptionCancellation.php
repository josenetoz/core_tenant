<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Stripe\CancelSubscriptionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionCancellation extends Model
{

    use HasFactory;

    protected $fillable = [
        'organization_id',
        'stripe_id',
        'reason',
        'coments',
        'rating',
    ];

    protected $casts = [
        'reason' => CancelSubscriptionEnum::class,
    ];


}
