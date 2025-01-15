<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\UserResource\Pages\CreateUser;


class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $modelLabel = 'Usuário';
    protected static ?string $modelLabelPlural = "Usuários";
    protected static ?string $title = 'Usuários do Tenant';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Dados do Usuário')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome Usuário')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(2),

                    Fieldset::make('Senha')
                    ->visible(fn ($livewire) => $livewire->mountedTableActionRecord === null)
                    ->schema([

                        TextInput::make('password')
                            ->password()
                            ->label('Senha')
                            // Exibe apenas ao criar
                            ->required(fn ($livewire) => $livewire->mountedTableActionRecord === null), // Requerido apenas ao criar

                    ])->columns(2),

                    Fieldset::make('Sistema')
                    ->schema([
                        Toggle::make('is_admin')
                        ->label('Administrador')
                        ->required(),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user')

            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->alignCenter(),

                TextColumn::make('name')
                    ->label('Nome'),

                TextColumn::make('email')
                    ->label('E-mail'),

                ToggleColumn::make('is_admin')
                    ->alignCenter()
                    ->label('Administrador'),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:m:s')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('email_verified_at')
                    ->label('Ativado em')
                    ->dateTime('d/m/Y H:m:s')
                    ->alignCenter()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['email_verified_at'] = now();

                    return $data;

                })
            ])
            ->actions([

                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    Action::make('Resetar Senha')
                    ->requiresConfirmation()
                    ->action(function (User $user) {
                        $newPassword = Str::random(8);

                        // Define a nova senha criptografada
                        $user->password = Hash::make($newPassword);
                        $user->save();

                        // Envia o e-mail com a nova senha
                        Mail::to($user->email)->send(new PasswordResetMail($newPassword));


                        Notification::make()
                            ->title('Senha Alterada com Sucesso')
                            ->body('Um Email foi enviado para o usuário com a nova senha')
                            ->success()
                            ->send();

                    })
                    ->color('warning') // Defina a cor, como amarelo para chamar atenção
                    ->icon('heroicon-o-key'), // Ícone da chave
                ]),



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
