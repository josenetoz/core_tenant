<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Forms\Components\Button;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\App\Resources\UserResource\Pages;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\UserResource\RelationManagers;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'fas-user-plus';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?string $navigationLabel = 'Meus Usuários';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $modelLabelPlural = "Usuários";
    protected static ?int $navigationSort = 2;
    protected static bool $isScopedToTenant = true;


    public static function form(Form $form): Form
    {
        return $form

            ->schema([

                Section::make('Dados do usuário')
                    ->description('Preencha os dados do usuário, a senha de acesso será gerada automaticamente e enviada para o e-mail do seu usuário.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->prefixIcon('fas-id-card')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->prefixIcon('fas-envelope')
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'E-mail já cadastrado.',
                            ])
                            ->required()
                            ->maxLength(255),
                        PhoneNumber::make('phone')
                            ->mask('(99) 99999-9999')
                            ->required()
                            ->prefixIcon('fas-phone'),

                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('Foto')
                    ->circular()
                    ->getStateUsing(function ($record) {
                        return $record->getFilamentAvatarUrl();
                    })
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),

                ToggleColumn::make('is_active')
                    ->label('Ativo')
                    ->sortable()
                    ->alignCenter()
                    ->beforeStateUpdated(function ($record, $state) {
                        $record->update(['is_active' => $state]);
                        if($state === true) {
                            Notification::make()
                            ->title('Acesso Liberado')
                            ->body("O Acesso do Usuário {$record->name} foi liberado")
                            ->success()
                            ->send();
                        }else{
                            Notification::make()
                            ->title('Acesso Desativado')
                            ->body("O Acesso do Usuário {$record->name} foi Desativado")
                            ->warning()
                            ->send();
                        }

                    }),
                Tables\Columns\IconColumn::make('is_tenant_admin')
                    ->label('Dono do Tenant')
                    ->alignCenter()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
