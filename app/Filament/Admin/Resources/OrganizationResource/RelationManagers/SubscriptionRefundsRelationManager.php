<?php

namespace App\Filament\Admin\Resources\OrganizationResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Stripe\RefundSubscriptionEnum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SubscriptionRefundsRelationManager extends RelationManager
{
    protected static string $relationship = 'subscription_refunds';

    public function form(Form $form): Form
    {
        return $form
            ->schema([



            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('object')
            ->columns([
                Tables\Columns\TextColumn::make('object'),
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
