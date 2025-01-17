<?php

namespace App\Filament\Admin\Resources\PromotionCodeResource\Pages;

use App\Filament\Admin\Resources\PromotionCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPromotionCode extends ViewRecord
{
    protected static string $resource = PromotionCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
