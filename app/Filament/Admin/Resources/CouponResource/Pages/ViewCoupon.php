<?php

namespace App\Filament\Admin\Resources\CouponResource\Pages;

use App\Filament\Admin\Resources\CouponResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCoupon extends ViewRecord
{
    protected static string $resource = CouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
