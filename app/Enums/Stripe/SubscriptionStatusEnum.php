<?php

namespace App\Enums\Stripe;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SubscriptionStatusEnum: string implements HasLabel, HasColor
{

    case TRIALING = 'trialing';
    case ACTIVE = 'active';
    case INCOMPLETE = 'incomplete';
    case INCOMPLETE_EXPIRED = 'incomplete_expired';
    case PAST_DUE = 'past_due';
    case UNPAID = 'unpaid';
    case CANCELED = 'canceled';
    case PAUSED = 'paused';
    
        public function getLabel(): ?string
        {
            return match ($this) {
                
                self::TRIALING => 'Período Teste',
                self::ACTIVE => 'Ativa',
                self::INCOMPLETE => 'Em Validação',
                self::INCOMPLETE_EXPIRED => 'Cancelado',
                self::PAST_DUE => 'Aguardando Pagamento',
                self::UNPAID => 'Cartão Inválido',
                self::CANCELED => 'Cancelado',
                self::PAUSED => 'Pausado',
            };
        }

        public function getColor(): string|array|null
        {
            return match ($this) {
                
                self::TRIALING => 'gray',
                self::ACTIVE => 'success',
                self::INCOMPLETE => 'warning',
                self::INCOMPLETE_EXPIRED => 'danger',
                self::PAST_DUE => 'warning',
                self::UNPAID => 'danger',
                self::CANCELED => 'danger',
                self::PAUSED => 'warning',

            };
        }
}



