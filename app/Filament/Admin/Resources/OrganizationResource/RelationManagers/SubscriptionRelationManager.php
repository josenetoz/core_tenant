<?php

namespace App\Filament\Admin\Resources\OrganizationResource\RelationManagers;


use DB;
use Stripe\StripeClient;
use Filament\Tables\Table;
use Illuminate\Support\Env;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Enums\Stripe\ProductCurrencyEnum;
use App\Enums\Stripe\ProductIntervalEnum;
use App\Enums\Stripe\Refunds\RefundSubscriptionEnum;
use App\Enums\Stripe\SubscriptionStatusEnum;
use Leandrocfe\FilamentPtbrFormFields\Money;
use App\Services\Stripe\Refund\CreateRefundService;
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
                    ->formatStateUsing(fn($state) => SubscriptionStatusEnum::from($state)->getLabel())
                    ->color(fn($state) => SubscriptionStatusEnum::from($state)->getColor())
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('stripe_id')
                    ->label('Id Subscription'),

                TextColumn::make('stripe_period')
                    ->label('Tipo do Plano')
                    ->getStateUsing(function ($record) {
                        // Acessa o preço relacionado via o relacionamento definido
                        return $record->price->interval;
                    })
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('stripe_price')
                    ->label('Valor do Plano')
                    ->getStateUsing(function ($record) {
                        // Acessa o preço relacionado via o relacionamento definido
                        return $record->price->unit_amount;
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
            ->headerActions([])
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

                    Action::make('Gerar Reembolso')
                        ->requiresConfirmation()
                        ->form([

                            Fieldset::make('Dados do Plano')
                                ->schema([
                                    TextInput::make('stripe_period')
                                        ->label('Tipo do Plano')
                                        ->readOnly()
                                        ->default(function ($record) {
                                            return $record->price->interval;
                                        }),

                                    TextInput::make('stripe_price')
                                        ->label('Valor do Plano')
                                        ->readOnly()
                                        ->default(function ($record) {
                                            $price = $record->price ? $record->price->unit_amount : 0;
                                            return 'R$ ' . number_format($price, 2, ',', '.');  // Exemplo: R$ 599,99
                                        }),
                                ])->columns(2),

                            Fieldset::make('Valores')
                                ->schema([

                                    Money::make('amount')
                                        ->label('Devolver')
                                        ->default('100,00')
                                        ->required()
                                        ->rule(function ($get) {

                                            $stripePrice = $get('stripe_price') ? filter_var($get('stripe_price'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;

                                            return "lte:{$stripePrice}";
                                        })
                                        ->validationAttribute('amount')
                                        ->validationMessages([
                                            'lte' => 'O valor não pode ser maior que o valor do plano.',
                                        ]),

                                    Select::make('currency')
                                        ->label('Moeda')
                                        ->options(ProductCurrencyEnum::class)
                                        ->required(),

                                ])->columns(2),

                            Fieldset::make('Motivo do Cancelamento')
                                ->schema([
                                    Select::make('reason')
                                        ->label('Selecione o Motivo')
                                        ->options(RefundSubscriptionEnum::class)
                                        ->required(),
                                ])->columns(1),

                            Fieldset::make('Id Pagamento')
                                ->schema([
                                    TextInput::make('payment_intent')
                                        ->label('Id Pagamento')
                                        ->readOnly()
                                        ->default(function ($record) {
                                            return $record->payment_intent;
                                        }),
                                ])->columns(1),
                        ])

                        ->requiresConfirmation()
                        ->modalHeading('Gerar Reembolso')
                        ->modalDescription()
                        ->slideOver()
                        ->color('warning')
                        ->icon('fas-hand-holding-dollar')
                        ->action(function (Action $action, $record, array $data) {


                            try {
                            //$refundService = new CreateRefundService();
                            //$refundService->processRefund($record->id, $data);

                            Notification::make()
                            ->title('Reembolso Gerado')
                            ->body('Reembolso gerado com Sucesso')
                            ->success()
                            ->send();

                            } catch (\Exception $e) {

                            Notification::make()
                                ->title('Erro ao Criar Preço')
                                ->body('Ocorreu um erro ao gerar reembolso na Stripe: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }

                        }),

                    Action::make('Baixar Invoice')
                        ->label('Baixar Invoice')
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn($record) => $record->invoice_pdf)
                        ->tooltip('Baixar PDF da Fatura')
                        ->color('primary'),

                ])
            ])

            ->bulkActions([]);
    }
}
