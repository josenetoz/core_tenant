<?php

namespace App\Services\Stripe\Refund;

use Exception;
use Stripe\Refund;
use Stripe\Stripe;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\SubscriptionRefund;
use Illuminate\Support\Facades\Log;
use App\Services\Traits\StripeClientTrait;

class CreateRefundService
{
    use StripeClientTrait;


    public function __construct()
    {
        $this->initializeStripeClient();
    }

    /**
     * @param  int
     * @param  array
     * @return SubscriptionRefund
     * @throws \Exception
     */
    public function processRefund(int $subscriptionId, array $data): SubscriptionRefund
    {
        try {

            $subscription = Subscription::findOrFail($subscriptionId);

            $paymentIntent = $subscription->payment_intent;

            if (!$paymentIntent) {
                throw new Exception('Pagamento não encontrado para reembolso.');
            }

            // Criar o reembolso no Stripe
            $refund = $this->stripe->refunds->create([
                'payment_intent' => $paymentIntent,
                'amount' => (int)$data['amount'],
                'reason' => $data['reason'],
            ]);

            $refundRecord = SubscriptionRefund::create([
                'organization_id' => $subscription->organization_id,
                'subscription_id' => $subscription->id,
                'stripe_id' => $subscription->stripe_id,
                'refund_id' => $refund->id,
                'amount' => (int)$data['amount'],
                'reason' => $data['reason'],
                'currency' => $data['currency'],
                'status' => $refund->status,
            ]);


            return $refundRecord;

        } catch (Exception $e) {
            //Log::error('Erro ao processar o reembolso: ' . $e->getMessage());
            throw new Exception('Erro ao processar o reembolso no Stripe.' . $e->getMessage());
        }
    }
}
