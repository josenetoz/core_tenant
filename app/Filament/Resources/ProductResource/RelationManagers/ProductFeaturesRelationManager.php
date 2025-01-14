<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProductFeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'product_features';
    protected static ?string $modelLabel = 'Caracteristica do Plano';
    protected static ?string $modelLabelPlural = "Caracteristicas do Plano";
    protected static ?string $title = 'Caracteristicas do Plano';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Fieldset::make('Característica')
                ->schema([
                    TextInput::make('name')
                    ->label('Nome da Característica')
                    ->required()
                    ->maxLength(255),
                ])->columns(1),        

                Fieldset::make('Descrição da Característica')
                ->schema([
                    Textarea::make('description')
                    ->label('Descrição da Característica')
                    ->required()
                    ->maxLength(255),
                ])->columns(1),        
                             
            ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nome da Característica')
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Descrição da Característica'),

                ToggleColumn::make('is_active')
                    ->label('Ativo para cliente')
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
