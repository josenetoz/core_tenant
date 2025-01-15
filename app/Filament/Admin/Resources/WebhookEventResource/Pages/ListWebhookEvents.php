<?php

namespace App\Filament\Admin\Resources\WebhookEventResource\Pages;

use App\Filament\Admin\Resources\WebhookEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebhookEvents extends ListRecords
{
    protected static string $resource = WebhookEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
