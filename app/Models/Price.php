<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Stripe\ProductCurrencyEnum;
use App\Enums\Stripe\ProductIntervalEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'stripe_price_id',
        'currency',
        'is_active',
        'trial_period_days',
        'interval',
        'unit_amount',
    ];

    protected $casts = [
        'interval' => ProductIntervalEnum::class,
        'currency' => ProductCurrencyEnum::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
