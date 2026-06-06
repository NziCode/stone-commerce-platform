<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->spa()
            ->maxContentWidth(MaxWidth::Full)
            ->renderHook(
                'panels::head.end',
                fn () => '
                <style>
                    @import url("https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap");

                    *, html, body {
                        font-family: "Vazirmatn", sans-serif !important;
                        direction: rtl !important;
                    }

                    .fi-sidebar {
                        right: 0 !important;
                        left: auto !important;
                    }

                    .fi-sidebar-nav {
                        direction: rtl !important;
                    }

                    .fi-topbar {
                        direction: rtl !important;
                    }

                    .fi-header {
                        direction: rtl !important;
                        text-align: right !important;
                    }

                    .fi-main {
                        direction: rtl !important;
                    }

                    .fi-ta-cell {
                        text-align: right !important;
                    }

                    .fi-fo-field-wrp {
                        direction: rtl !important;
                        text-align: right !important;
                    }

                    .fi-fo-label {
                        text-align: right !important;
                    }

                    .fi-breadcrumbs {
                        direction: rtl !important;
                    }

                    .fi-badge {
                        font-family: "Vazirmatn", sans-serif !important;
                    }
                </style>
                '
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\LatestOrders::class,
                \App\Filament\Widgets\ProductStatusChart::class,
                \App\Filament\Widgets\RevenueChart::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(FilamentShieldPlugin::make());
    }
}
