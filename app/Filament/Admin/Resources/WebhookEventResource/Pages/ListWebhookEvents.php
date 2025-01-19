<?php

namespace App\Filament\Admin\Resources\WebhookEventResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\WebhookEventResource;
use App\Filament\Admin\Resources\WebhookEventResource\Widgets\StatsWebhookOverview;

class ListWebhookEvents extends ListRecords
{
    protected static string $resource = WebhookEventResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            StatsWebhookOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
