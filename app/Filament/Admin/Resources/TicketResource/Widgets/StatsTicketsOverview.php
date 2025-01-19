<?php

namespace App\Filament\Admin\Resources\TicketResource\Widgets;

use App\Models\Price;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsTicketsOverview extends BaseWidget
{

     protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Atendimento em Progresso', Ticket::where('status', 'in_progress')->whereNull('closed_at')->count())
                ->description('Total')
                ->descriptionIcon('heroicon-s-users')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]),

            Stat::make('Bugs Abertos', Ticket::where('type', 'problem')->whereNull('closed_at')->count())
                ->description('Bugs')
                ->descriptionIcon('heroicon-s-bug-ant')
                ->color('danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Melhorias Propostas', Ticket::where('type','enhancement')->count())
                ->description('Melhorias')
                ->color('success')
                ->descriptionIcon('heroicon-s-cog-6-tooth')
                ->chart([7, 3, 4, 5, 6, 3, 5, 5]),

            Stat::make('Tempo Médio de Resolução', function () {
                    $averageTime = Ticket::whereNotNull('closed_at')
                        ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, closed_at)) as avg_resolution_time')
                        ->value('avg_resolution_time');

                    // Se o valor for menor que 0 ou nulo, retorna 0 horas
                    return ($averageTime > 0 ? $averageTime : 0) . ' horas';
                })
                ->description('tempo')
                ->color('warning')
                ->descriptionIcon('heroicon-s-clock')
                ->chart([7, 3, 4, 5, 6, 3, 5, 5]),
            ];
        }



}
