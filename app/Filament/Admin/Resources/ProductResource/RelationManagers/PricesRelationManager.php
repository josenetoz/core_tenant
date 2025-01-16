<?php

namespace App\Filament\Admin\Resources\ProductResource\RelationManagers;

use Stripe\Price;
use Stripe\Stripe;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Enums\Stripe\ProductCurrencyEnum;
use App\Enums\Stripe\ProductIntervalEnum;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Services\Stripe\Price\CreateStripePriceService;
use Filament\Resources\RelationManagers\RelationManager;

class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'prices';
    protected static ?string $modelLabel = 'Preço';
    protected static ?string $modelLabelPlural = "Preço";
    protected static ?string $title = 'Valores dos Produtos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('currency')
                    ->label('Moeda')
                    ->required()
                    ->searchable()
                    ->options(ProductCurrencyEnum::class),

                Select::make('interval')
                    ->label('Intervalo de Cobrança')
                    ->options(ProductIntervalEnum::class)
                    ->searchable()
                    ->required(),

                Money::make('unit_amount')
                    ->label('Preço')
                    ->default('100,00')
                    ->required(),

                TextInput::make('trial_period_days')
                    ->label('Periodo de testes')
                    ->required()
                    ->default(0)
                    ->integer(),

            ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('price')
            ->columns([
                TextColumn::make('stripe_price_id')
                    ->label('Id Gateway Pagamento')
                    ->sortable(),

                TextColumn::make('currency')
                    ->label('Moeda')
                    ->badge()
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('interval')
                    ->label('Intervalo de Cobrança')
                    ->badge()
                    ->sortable()
                    ->alignCenter(),

                ToggleColumn::make('is_active')
                    ->label('Ativo para cliente')
                    ->alignCenter(),

                TextColumn::make('unit_amount')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('trial_period_days')
                    ->label('Dias de Teste')
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function ($record) {
                        try {

                            $createStripePriceService = new CreateStripePriceService();
                            $createStripePriceService->execute($record);

                        } catch (\Exception $e) {

                            Notification::make()
                                ->title('Erro ao Criar Preço')
                                ->body('Ocorreu um erro ao criar o preço no Stripe: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
