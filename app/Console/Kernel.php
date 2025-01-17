<?php

namespace App\Console;

use App\Console\Commands\CleanWebhookEvents;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Carrega as configurações de agendamento
        $scheduleConfig = config('webhook-events.schedule');
        $time = $scheduleConfig['time'];

        // Verifica o tipo de agendamento
        switch ($scheduleConfig['type']) {
            case 'daily':
                // Agendamento diário
                $schedule->command(CleanWebhookEvents::class)->dailyAt($time);
                break;

            case 'weekly_on':
                // Agendamento semanal
                $schedule->command(CleanWebhookEvents::class)->weeklyOn(
                    $this->dayOfWeekToInteger($scheduleConfig['weekly_day']), // Chama o método aqui
                    $time
                );
                break;

            default:
                // Caso o tipo de agendamento não seja válido
                throw new \InvalidArgumentException("Tipo de agendamento inválido: {$scheduleConfig['type']}");
        }
    }

    /**
     * Converte o dia da semana em string para o valor inteiro correspondente.
     *
     * @param string $day
     * @return int
     */
    private function dayOfWeekToInteger(string $day): int
    {
        $days = [
            'Sunday' => 0,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
        ];

        // Retorna o valor correspondente ou lança uma exceção se o dia for inválido
        return $days[$day] ?? throw new \InvalidArgumentException("Dia da semana inválido: {$day}");
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
