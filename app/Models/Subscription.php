<?php

namespace App\Models;

use App\Enums\Stripe\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $casts = [
        'stripe_status'=>SubscriptionStatusEnum::class,
    ];

    // Método para garantir a conversão explícita
   
    
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

