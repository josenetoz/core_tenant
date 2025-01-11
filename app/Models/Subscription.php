<?php

namespace App\Models;

use App\Enums\Stripe\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;
  
    protected $fillable = [
        
        'organization_id',
        'type',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'quantity',
        'trial_ends_at',
        'ends_at',
    ];

    protected $casts = [
        
        'stripe_status' => SubscriptionStatusEnum::class,
        
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

