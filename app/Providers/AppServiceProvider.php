<?php

namespace App\Providers;


use Livewire\Livewire;
use App\Models\Organization;
use Laravel\Cashier\Cashier;
use App\Http\Livewire\PayloadModal;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void

    {
        require_once app_path('Support/helpers.php');
        Cashier::useCustomerModel(Organization::class);

    }
}
