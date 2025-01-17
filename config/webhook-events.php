<?php

return [
    // Período de retenção para limpar registros de webhook
    'retention_period' => 1,  // Em dias, por exemplo 2 dias

    // Configuração de agendamento para o comando de limpeza
    'schedule' => [
        'type' => 'daily',   // Tipo de agendamento: 'daily' ou 'weekly_on'
        'time' => '15:33',   // Hora específica para o agendamento diário
        // Para o agendamento semanal, você pode adicionar o dia da semana
        // 'weekly_day' => 'Sunday',  // Exemplo para agendar no domingo
    ],
];
