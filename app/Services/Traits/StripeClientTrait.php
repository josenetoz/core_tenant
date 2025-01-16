<?php

namespace App\Services\Traits;

use Stripe\StripeClient;

trait StripeClientTrait
{
    protected StripeClient $stripe;

    public function initializeStripeClient(): void
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }
}
