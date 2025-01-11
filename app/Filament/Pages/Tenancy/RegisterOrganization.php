<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use App\Services\PaymentGateway\Connectors\AsaasConnector;
use App\Services\PaymentGateway\Gateway;


class RegisterOrganization extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Registrar Empresa';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('name')
                    ->label('Nome da Empresa')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('slug', Str::slug($state));
                    }),

                TextInput::make('email')
                    ->label('E-mail Principal')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                PhoneNumber::make('phone')
                    ->label('Celular da Empresa')
                    ->required()
                    ->mask('(99) 99999-9999'),    
                   
                Document::make('document_number')
                    ->label ('Documento da Empresa (CPF ou CNPJ)') 
                    ->validation(false)
                    ->required()
                    ->dynamic(),

                TextInput::make('slug')
                    ->label('Essa será a URL da sua empresa')
                    ->readonly(),
            ]);
    }

    protected function handleRegistration(array $data): Organization
    {
       
        $organization = Organization::create($data);
        $organization->members()->attach(Auth::user());  

        return $organization;
    }

}
