<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Models\Organization;
use App\Filament\Pages\Backup\Backup; // Ensure this class exists in the specified namespace
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use App\Http\Middleware\VerifyIsAdmin;
use App\Filament\Billing\BillingProvider;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->sidebarFullyCollapsibleOnDesktop()
            ->databaseNotifications()
            ->globalSearch(true)
          
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Aplicativo')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url('/app')
            ])
            ->font('Inter')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->navigationGroups([
                'Planos',
                'Administração',
                'Sistema',
                
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
              
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                VerifyIsAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])

            ->plugins([
                // ...
                FilamentJobsMonitorPlugin::make(),
                FilamentSpatieLaravelBackupPlugin::make()
                    ->usingPage(Backup::class)
                    ->usingPolingInterval('10s') // default value is 4s 
                    ->usingQueue('default') // default value is null
                    ->timeout(120) // default value is 120s
                ]);    
    }
}
