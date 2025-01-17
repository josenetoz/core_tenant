<?php

namespace App\Filament\Admin\Resources\PromotionCodeResource\Pages;

use App\Filament\Admin\Resources\PromotionCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPromotionCode extends EditRecord
{
    protected static string $resource = PromotionCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
