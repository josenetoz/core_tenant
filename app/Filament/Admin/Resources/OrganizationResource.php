<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Organization;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\OrganizationResource\Pages;
use App\Filament\Admin\Resources\OrganizationResource\RelationManagers;
use App\Filament\Admin\Resources\OrganizationResource\RelationManagers\UserRelationManager;
use App\Filament\Admin\Resources\OrganizationResource\RelationManagers\SubscriptionRelationManager;
use App\Filament\Admin\Resources\OrganizationResource\RelationManagers\SubscriptionRefundsRelationManager;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Administração';
    protected static ?string $navigationLabel = 'Tenant';
    protected static ?string $modelLabel = 'Tenant';
    protected static ?string $modelLabelPlural = "Tenants";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Dados da Empresa')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome da Empresa')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('slug', Str::slug($state));
                            })
                            ->maxLength(255),

                        Document::make('document_number')
                            ->label('Documento da Empresa (CPF ou CNPJ)')
                            ->validation(false)
                            ->required()
                            ->dynamic(),

                        TextInput::make('slug')
                            ->label('URL da Empresa')
                            ->readonly(),

                        TextInput::make('stripe_id')
                            ->label('Id Cliente Stripe')
                            ->readOnly()
                            ->maxLength(255),

                    ])->columns(3),

                Fieldset::make('Dados de Contato')
                    ->schema([
                        TextInput::make('email')
                            ->label('E-mail Empresa')
                            ->required(),

                        PhoneNumber::make('phone')
                            ->label('Telefone da Empresa')
                            ->required()
                            ->mask('(99) 99999-9999'),

                    ])->columns(2),

                Fieldset::make('Informações do Cartão')
                    ->schema([
                        Grid::make(5)->schema([

                            TextInput::make('pm_type')
                                ->label('Tipo de Pagamento')
                                ->readonly(),

                            TextInput::make('pm_last_four')
                                ->label('Últimos 4 Dígitos')
                                ->readonly(),

                            TextInput::make('card_exp_month')
                                ->label('Mês de Expiração')
                                ->readonly(),

                            TextInput::make('card_exp_year')
                                ->label('Ano de Expiração')
                                ->readonly(),

                            TextInput::make('card_country')
                                ->label('País do Cartão')
                                ->readonly(),
                        ]),
                    ])->columns(1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Cliente')
                    ->searchable(),

                TextColumn::make('document_number')
                    ->label('Documento')
                    ->searchable(),

                TextColumn::make('slug')
                    ->label('Url Tenant')
                    ->searchable(),

                TextColumn::make('latest_subscription_trial_ends_at')
                    ->label('Período de Teste')
                    ->getStateUsing(fn($record) => $record->subscriptions()->latest('trial_ends_at')->first()?->trial_ends_at)
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),

                TextColumn::make('latest_subscription_ends_at')
                    ->label('Data de Expiração')
                    ->getStateUsing(fn($record) => $record->subscriptions()->latest('ends_at')->first()?->ends_at)
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:m:s')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
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
            UserRelationManager::class,
            SubscriptionRelationManager::class,
            SubscriptionRefundsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'view' => Pages\ViewOrganization::route('/{record}'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {

        return false;
    }
}
