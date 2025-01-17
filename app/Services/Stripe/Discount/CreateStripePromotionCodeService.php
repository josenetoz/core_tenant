<?php

namespace App\Services\Stripe\Discount;

use Exception;



use App\Services\Traits\StripeClientTrait;
use Stripe\PromotionCode;

class CreateStripePromotionCodeService
{
    use StripeClientTrait;

    public function __construct()
    {
        $this->initializeStripeClient();
    }

    /**
     * @param array
     * @return Discount
     * @throws Exception
     */
    public function createPromotionCode(array $data): PromotionCode
    {

        dd($data);

        try {




            return $this->stripe->promotionCodes->create([

            ]);

        } catch (Exception $e) {

            throw new Exception('Falha ao criar cliente no Stripe: ' . $e->getMessage());
        }
    }
}
