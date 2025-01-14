<?php

namespace App\Filament\Pages\Tenancy;

use Stripe\Stripe;
use Stripe\Customer;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use App\Services\PaymentGateway\Gateway;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use App\Services\PaymentGateway\Connectors\AsaasConnector;


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
        // Configura a chave secreta da API da Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Cria um novo cliente no Stripe
            $customer = Customer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'description' => 'Cliente registrado no sistema SaaS',
            ]);
        } catch (\Exception $e) {
            // Lida com erros ao criar o cliente no Stripe
            throw new \Exception('Falha ao criar cliente no Stripe: ' . $e->getMessage());
        }

        // Cria a organização no banco de dados com o ID do Stripe
        $organization = Organization::create(array_merge($data, [

            'stripe_id' => $customer->id,

        ]));

        // Vincula o usuário autenticado como membro da organização
        $organization->members()->attach(Auth::user());

        return $organization;
    }


}
