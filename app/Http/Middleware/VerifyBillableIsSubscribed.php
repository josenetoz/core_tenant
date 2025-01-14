<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Data\Cashier\Stripe;
use App\Models\Organization;
use Illuminate\Http\Request;
use Filament\Pages\Dashboard;
use function App\Support\tenant;
use App\Data\Stripe\StripeDataLoader;
use Symfony\Component\HttpFoundation\Response;

class VerifyBillableIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();
        if ($user && $user->is_admin) {
            return $next($request);
        }

        $tenant = tenant(Organization::class);

        $stripeConfig = StripeDataLoader::getProductsData();

        //dd($stripeConfig);
        foreach ($stripeConfig as $plan) {
            // Verifica se o tenant está subscrito ao produto
            if (isset($plan['stripe_id']) && $tenant->subscribedToProduct($plan['stripe_id'])) {
                return $next($request);
            }
        }

        // Verifica se a ação de assinatura está sendo solicitada
        if ($request->has('action') && $request->get('action') === 'subscribe') {
            return $next($request);
        }

        // Redireciona para a página de assinatura se nenhuma assinatura for encontrada
        return redirect(Dashboard::getUrl(['action' => 'subscribe']));
    }
}
