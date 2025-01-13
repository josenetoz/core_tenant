<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class TicketResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'ticket_responses';
    protected static ?string $modelLabel = 'Tratativa';
    protected static ?string $modelLabelPlural = "Tratativas";
    protected static ?string $title = 'Tratativa do Ticket';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Fieldset::make('Tratativa do Ticket')
                ->schema([
                    Textarea::make('message')
                        ->label('Tratativa')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(1),

                Fieldset::make('Anexos')
                ->schema([
                    FileUpload::make('file')
                        ->multiple()
                        ->label('Arquivos'),
                ])->columns(1),
         
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
    
            ->columns([
                TextColumn::make('user.name')
                    ->label('Responsável'),

                TextColumn::make('message')
                    ->label('Tratativa'),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime( 'd/m/Y H:m:s'),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime( 'd/m/Y H:m:s'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
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
