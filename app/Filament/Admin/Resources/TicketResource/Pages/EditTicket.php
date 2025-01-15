<?php

namespace App\Filament\Admin\Resources\TicketResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\TicketResource;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        $ticket = $this->record->fresh(); // Recarrega o ticket atualizado do banco de dados
        $user = User::find($ticket->user_id);

        $status = strtolower(trim($ticket->status->value)); // Normaliza o valor do enum

        if (in_array($status, ['resolved', 'closed'])) {
            $ticket->update(['closed_at' => now()]); // Atualiza diretamente o campo 'closed_at' no banco de dados
        }

        Notification::make()
            ->title('Chamado Atualizado')
            ->body("Seu Chamado de N. {$ticket->id} foi atualizado. Confira as atualizações.")
            ->success()
            ->actions([
                Action::make('Visualizar')
                    ->url(TicketResource::getUrl('view', ['record' => $ticket->id])),
            ])
            ->sendToDatabase($user); // Envia a notificação para o usuário relacionado ao ticket
    }

}
