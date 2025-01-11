<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Stripe\StripeClient;
use Filament\Tables\Table;
use Illuminate\Support\Env;
use App\Models\Subscription;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Enums\Stripe\SubscriptionStatusEnum;

class SubscriptionRelationManager extends RelationManager
{
    protected static string $relationship = 'subscriptions';
    protected static ?string $modelLabel = 'Assinatura';
    protected static ?string $modelLabelPlural = "Assinaturas";
    protected static ?string $title = 'Subscrições do Tenant';
 
    protected function canCreate(): bool
    {
       return false;
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([

                TextColumn::make('stripe_status')
                    ->label('Assinatura')
                    ->sortable()
                    ->searchable()
                    ->alignCenter()
                    ->badge(),
                        

                TextColumn::make('type')
                    ->label('Plano'),

                TextColumn::make('stripe_id')
                    ->label('Id Gateway Pagamento'),

                TextColumn::make('stripe_price')
                    ->label('Valor do Plano'),
                    
                TextColumn::make('ends_at')
                    ->label('Data de Expiração')
                    ->dateTime('d/m/Y H:m:s'),
                     
            ])
            ->filters([
                //
            ])
            ->headerActions([
                
            ])
            ->actions([
                ActionGroup::make([
                Action::make('Cancelar Assinatura')
                ->requiresConfirmation()
                ->action(function (Action $action, $record) {                                     
                    $stripe = new StripeClient(Env::get('STRIPE_SECRET'));
                    $stripe->subscriptions->cancel($record->stripe_id);
                    Notification::make()
                        ->title('Assinatura Cancelada')
                        ->body('Assinatura cancelada com sucesso!')
                        ->success()
                        ->send();
                })
                ->color('danger') 
                ->icon('heroicon-o-key'), 
                
            ])
            ])
            ->bulkActions([
             
            ]);
           
    }
}
