<?php

namespace App\Console\Commands;

use App\Jobs\CleanWebhookEventsJob;
use Illuminate\Console\Command;

class CleanWebhookEvents extends Command
{
    protected $signature = 'webhook:clean';
    protected $description = 'Limpar registros antigos da tabela webhook_events com base no período de retenção';

    public function handle()
    {
        $retentionPeriod = config('webhook-events.retention_period');

        CleanWebhookEventsJob::dispatch($retentionPeriod);

        $this->info("Job de limpeza de eventos de webhook enfileirada com sucesso para registros mais antigos que {$retentionPeriod} dias.");
    }
}
