<?php

namespace App\Models;

use App\Enums\Stripe\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Leandrocfe\FilamentPtbrFormFields\Facades\FilamentPtbrFormFields;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'quantity',
        'current_period_start',
        'trial_ends_at',
        'ends_at',
        'hosted_invoice_url',
        'invoice_pdf',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function price()
    {
        return $this->hasOne(Price::class, 'stripe_price_id', 'stripe_price');
    }

}


