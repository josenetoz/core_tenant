<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use Filament\Actions;
use Stripe\StripeClient;
use Illuminate\Support\Env;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\ProductResource;
use App\Services\Stripe\Product\CreateStripeProductService;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            $createStripeProductService = new CreateStripeProductService();

            $data['stripe_id'] = $createStripeProductService->execute(
                $data['name'],
                $data['description'] ?? null
            );

            return $data;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao salvar produto: ' . $e->getMessage());
        }
    }
}
