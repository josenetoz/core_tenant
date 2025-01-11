<?php

declare(strict_types=1);

namespace App\Filament\Billing;

use Closure;
use LogicException;
use App\Models\Organization;
use Laravel\Cashier\Cashier;
use Filament\Pages\Dashboard;
use Laravel\Cashier\Billable;
use function App\Support\tenant;
use Illuminate\Http\RedirectResponse;
use Filament\Billing\Providers\Contracts\Provider;
use App\Http\Middleware\VerifyBillableIsSubscribed;

class BillingProvider implements Provider
{
    public function getRouteAction(): string|Closure|array
    {
        return static function (): RedirectResponse {
            $tenant = tenant(Organization::class);

            if ($tenant::class !== Cashier::$customerModel) {
                throw new LogicException('Filament tenant does not match the Cashier customer model');
            }

            if (! in_array(Billable::class, class_uses_recursive($tenant), true)) {
                throw new LogicException('Tenant model does not use Cashier Billable trait');
            }

            if (! $tenant->hasStripeId()) {
                $tenant->createAsStripeCustomer();
            }

            return $tenant->redirectToBillingPortal(Dashboard::getUrl());
        };
    }

    public function getSubscribedMiddleware(): string
    {
        return VerifyBillableIsSubscribed::class;
    }
}
