<?php

namespace App\Filament\Admin\Resources\OrganizationResource\Widgets;

use App\Models\Price;
use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsTenantOverview extends BaseWidget
{

     protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Tenants Cadastrados', Organization::count())
                ->description('Total desde o início')
                ->descriptionIcon('heroicon-s-users')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]),

            Stat::make('Total tenants', Subscription::where('stripe_status', 'active')->count())
                ->description('Atualmente ativos')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('info')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Valor Faturado', number_format(Price::sum('unit_amount'), 2, ',', '.'))
                ->description('Acumulado no período')
                ->color('success')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->chart([7, 3, 4, 5, 6, 3, 5, 5]),
            ];
        }



}
