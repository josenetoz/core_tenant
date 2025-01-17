<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\WebhookEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CleanWebhookEventsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels, Dispatchable;

    private int $retentionPeriod;

    /**
     * Create a new job instance.
     *
     * @param int $retentionPeriod
     */
    public function __construct(int $retentionPeriod)
    {
        $this->retentionPeriod = $retentionPeriod;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info("CleanWebhookEventsJob iniciado.");

        // Calcula a data limite com base no período de retenção
        $dateLimit = Carbon::now()->subDays($this->retentionPeriod);

        // Exclui os eventos mais antigos que a data limite
        $deletedCount = WebhookEvent::where('created_at', '<', $dateLimit)->delete();

        Log::info("{$deletedCount} registros excluídos.");

        // Log para auditoria (opcional)
        Log::info("{$deletedCount} registros de webhook mais antigos que {$this->retentionPeriod} dias foram excluídos.");
    }
}
