<?php

namespace App\Models;

use Stripe\StripeClient;
use Illuminate\Support\Env;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'active',
        'trial_days',
        'stripe_id',
    ];

    protected $casts = [
        'image' => 'array'
    ];   

  
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }
}
