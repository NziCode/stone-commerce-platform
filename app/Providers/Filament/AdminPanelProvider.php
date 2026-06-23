<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
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
use App\Http\Middleware\SetAdminLocale;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Auth\SuperUserAuthenticator::class)
            ->emailVerification(false)
            ->colors([
                'primary' => Color::hex('#ff5a1f'),
                'danger'  => Color::hex('#e0473a'),
                'success' => Color::hex('#1f9d55'),
                'warning' => Color::hex('#e0a400'),
                'info'    => Color::hex('#123a7a'),
                'gray'    => Color::Slate,
            ])
            ->brandName('EN Trading Group')
            ->brandLogoHeight('2.25rem')
            ->brandLogo(fn () => \App\Models\Setting::get('site_logo')
                ? asset(\App\Models\Setting::get('site_logo'))
                : null)
            ->favicon(asset('assets/images/favicon.ico'))
            ->maxContentWidth(MaxWidth::Full)
            ->authGuard('web')
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()

            // ── Set <html dir/lang> as early as possible ─────────
            ->renderHook(
                'panels::head.start',
                function () {
                    $locale = app()->getLocale();
                    $isRtl  = in_array($locale, ['fa', 'ar']);
                    $dir    = $isRtl ? 'rtl' : 'ltr';

                    return new \Illuminate\Support\HtmlString("
                        <script>
                            document.documentElement.setAttribute('dir', '{$dir}');
                            document.documentElement.setAttribute('lang', '{$locale}');
                        </script>
                    ");
                }
            )

            // ── RTL/LTR داینامیک ────────────────────────────────
            ->renderHook(
                'panels::head.end',
                function () {
                    $locale    = app()->getLocale();
                    $isRtl     = in_array($locale, ['fa', 'ar']);
                    $dir       = $isRtl ? 'rtl' : 'ltr';
                    $fontUrl   = $isRtl
                        ? 'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap'
                        : 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap';
                    $fontFamily = $isRtl ? '"Vazirmatn", sans-serif' : '"Inter", sans-serif';

                    $rtlCss = $isRtl ? '
                        .fi-sidebar { right: 0 !important; left: auto !important; border-left: 1px solid rgb(var(--gray-200)) !important; border-right: none !important; }
                        .fi-sidebar-nav, .fi-topbar nav, .fi-header, .fi-main, .fi-breadcrumbs ol, .fi-fo-field-wrp, .fi-ta-cell, .fi-fo-label { direction: rtl !important; text-align: right !important; }
                        .fi-topbar { padding-right: 1rem; }
                        .fi-sidebar-nav-groups { padding-right: 0.75rem !important; padding-left: 0.25rem !important; }
                        .fi-breadcrumbs ol { flex-direction: row-reverse !important; }
                        .fi-ta-header-cell { text-align: right !important; }
                    ' : '
                        .fi-sidebar { left: 0 !important; right: auto !important; }
                        *, html, body { direction: ltr !important; text-align: left !important; }
                    ';

                    return new \Illuminate\Support\HtmlString('
                        <link href="' . $fontUrl . '" rel="stylesheet">
                        <link href="' . asset('assets/css/admin-modern.css') . '" rel="stylesheet">
                        <style>
                            *, html, body { font-family: ' . $fontFamily . ' !important; }
                            ' . $rtlCss . '
                        </style>
                    ');
                }
            )

            // ── Language Switcher in Topbar ──────────────────────
            ->renderHook(
                'panels::topbar.end',
                fn () => view('filament.widgets.admin-language-switcher', [
                    'languages'     => \App\Services\LanguageService::getActive(),
                    'currentLocale' => app()->getLocale(),
                ])
            )

            // ── Navigation Groups ────────────────────────────────
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn () => __('admin.products')),

                NavigationGroup::make()
                    ->label(fn () => __('admin.orders')),

                NavigationGroup::make()
                    ->label(fn () => __('admin.content')),

                NavigationGroup::make()
                    ->label(fn () => __('admin.appearance')),

                NavigationGroup::make()
                    ->label(fn () => __('admin.users')),

                NavigationGroup::make()
                    ->label(fn () => __('admin.management')),

                NavigationGroup::make()
                    ->label(fn () => __('admin.settings')),
            ])

            // ── Resources / Pages / Widgets ──────────────────────
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\ManageSettings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\QuickActions::class,
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
                SetAdminLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
