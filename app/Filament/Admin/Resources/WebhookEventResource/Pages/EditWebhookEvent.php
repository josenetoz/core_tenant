<?php

namespace App\Filament\Admin\Resources\WebhookEventResource\Pages;

use App\Filament\Admin\Resources\WebhookEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebhookEvent extends EditRecord
{
    protected static string $resource = WebhookEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
