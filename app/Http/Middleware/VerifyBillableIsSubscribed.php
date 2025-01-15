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
        $tenant = tenant(Organization::class);

        // Verifica se o usuario é SuperAdmin e libera entrada sem subscription
        $user = $request->user();
        if ($user && $user->is_admin) {
            return $next($request);
        }

        // Verifica se o tenant tem subscription ativa e libera entrada
        if ($tenant->subscriptions()->active()->exists()) {
            return $next($request);
        }


        // Verifica se a ação de assinatura está sendo solicitada
        if ($request->has('action') && $request->get('action') === 'subscribe') {
            return $next($request);
        }

        // Redireciona para a página de assinatura se nenhuma assinatura for encontrada
        return redirect(Dashboard::getUrl(['action' => 'subscribe']));
    }
}
