<?php

namespace App\Filament\Admin\Resources\OrganizationResource\RelationManagers;


use DB;
use Stripe\StripeClient;
use Filament\Tables\Table;
use Illuminate\Support\Env;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Enums\Stripe\SubscriptionStatusEnum;
use Filament\Resources\RelationManagers\RelationManager;
use App\Services\Stripe\Subscription\CancelSubscriptionService;


    class SubscriptionRelationManager extends RelationManager
    {
        protected static string $relationship = 'subscriptions';
        protected static ?string $modelLabel = 'Assinatura';
        protected static ?string $modelLabelPlural = "Assinaturas";
        protected static ?string $title = 'Subscrições do Tenant';


        public function table(Table $table): Table
        {
            return $table

                ->columns([

                    TextColumn::make('stripe_status')
                        ->label('Status')
                        ->badge()
                        ->formatStateUsing(fn ($state) => SubscriptionStatusEnum::from($state)->getLabel())
                        ->color(fn ($state) => SubscriptionStatusEnum::from($state)->getColor())
                        ->alignCenter()
                        ->sortable()
                        ->searchable(),

                    TextColumn::make('stripe_id')
                        ->label('Id Subscription'),

                    TextColumn::make('stripe_period')
                        ->label('Tipo do Plano')
                        ->getStateUsing(function ($record) {
                            // Acessa o preço relacionado via o relacionamento definido
                            return $record->price->interval ;
                        })
                        ->alignCenter()
                        ->sortable(),

                    TextColumn::make('stripe_price')
                        ->label('Valor do Plano')
                        ->getStateUsing(function ($record) {
                            // Acessa o preço relacionado via o relacionamento definido
                            return $record->price->unit_amount ;
                        })
                        ->money('brl')
                        ->alignCenter()
                        ->sortable(),

                    TextColumn::make('ends_at')
                        ->label('Expira em')
                        ->alignCenter()
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
                    ->action(function (Action $action, $record, array $data) {
                        $cancellationService = new CancelSubscriptionService();
                        $cancellationService->cancel($record, $data);
                    })
                    ->color('danger')
                    ->icon('heroicon-o-key'),

                    Action::make('Baixar Invoice')
                        ->label('Baixar Invoice')
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn ($record) => $record->invoice_pdf)
                        ->tooltip('Baixar PDF da Fatura')
                        ->color('primary'),

            ])
                ])

                ->bulkActions([

                ]);

        }

    }
