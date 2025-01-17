<?php

namespace App\Filament\Admin\Resources\PromotionCodeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\PromotionCodeResource;
use App\Services\Stripe\Discount\CreateStripePromotionCodeService;

class CreatePromotionCode extends CreateRecord
{
    protected static string $resource = PromotionCodeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {

            $CreateStripePromotionCodeService = new CreateStripePromotionCodeService();

            $CreateStripePromotionCodeService->createPromotionCode($data);

            return $data;

        } catch (\Exception $e) {

            throw new \Exception('Erro ao salvar produto: ' . $e->getMessage());
        }
    }
}
