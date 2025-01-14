<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Stripe\StripeClient;
use Filament\Tables\Table;
use Illuminate\Support\Env;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\PricesRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\ProductFeaturesRelationManager;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'fas-hand-holding-dollar';
    protected static ?string $navigationGroup = 'Planos';
    protected static ?string $navigationLabel = 'Planos';
    protected static ?string $modelLabel = 'Plano';
    protected static ?string $modelLabelPlural = "Planos";
    protected static ?int $navigationSort = 1;
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Fieldset::make('Label')
                    ->schema([
                        TextInput::make('stripe_id')
                            ->label('Id Plano Stripe')
                            ->readOnly(),
                    ]),

                Fieldset::make('Label')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome do Plano')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('description')
                            ->label('Descrição do Plano')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                    Fieldset::make('Imagem do Plano')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Imagem do Plano')
                            ->image()
                            ->imageEditor(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('stripe_id')
                    ->label('Id Plano Stripe')
                ->searchable(),

                TextColumn::make('description')
                    ->label('Descrição do Plano')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nome do Plano')
                    ->searchable(),

                TextColumn::make('prices_count')
                    ->label('Preços Cadastrados')
                    ->alignCenter()
                    ->sortable()
                    ->getStateUsing(fn ($record) => (string) $record->prices()->count()),

                TextColumn::make('features_count')
                    ->label('Características')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => (string) $record->product_features()->where('is_active', true)->count()),

                ToggleColumn::make('is_active')
                    ->label('Ativo')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->action(function (Action $action, $record) {
                    $stripe = new StripeClient(Env::get('STRIPE_SECRET'));
                    $stripe->products->delete($record->stripe_id);

                }),
            ])
            ->bulkActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            PricesRelationManager::class,
            ProductFeaturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

}
