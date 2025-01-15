<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StripeWebhookController;

Route::get('/', function () {
    return redirect('/app');
});

//Rota do webhook custom stripe
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);



