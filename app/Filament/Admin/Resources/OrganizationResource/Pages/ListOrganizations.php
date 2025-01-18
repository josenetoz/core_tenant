<?php

namespace App\Filament\Admin\Resources\OrganizationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\OrganizationResource;
use App\Filament\Admin\Resources\OrganizationResource\Widgets\RevenueWidget;
use App\Filament\Admin\Resources\OrganizationResource\Widgets\TotalTenantsWidget;
use App\Filament\Admin\Resources\OrganizationResource\Widgets\ActiveTenantsWidget;
use App\Filament\Admin\Resources\OrganizationResource\Widgets\StatsTenantOverviewWidget;

class ListOrganizations extends ListRecords
{
    protected static string $resource = OrganizationResource::class;


    protected function getHeaderWidgets(): array
    {
        return [
            StatsTenantOverviewWidget::class,

        ];
    }
    public function getHeaderWidgetsColumns(): int    {
        return 3;  // Definindo 3 colunas para os widgets
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
