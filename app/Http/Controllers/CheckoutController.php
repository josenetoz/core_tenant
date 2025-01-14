<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Página de sucesso do checkout.
     */
    public function success(Request $request)
    {
        return view('checkout.success');
    }

    /**
     * Página de cancelamento do checkout.
     */
    public function cancel(Request $request)
    {
        return view('checkout.cancel');
    }
}
