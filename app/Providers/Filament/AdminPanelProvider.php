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
            ->brandName('Stone Commerce')
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

                    // Fixes the sidebar nav and the page body fighting over the same
                    // scroll container (two overlapping scrollbars, scroll position
                    // jumping between them). Each now owns its own independent,
                    // full-height scroll region.
                    $scrollFixCss = '
                        html, body { height: 100%; overflow: hidden; }
                        .fi-layout { height: 100vh; overflow: hidden; }
                        .fi-sidebar { height: 100vh; position: sticky; top: 0; overflow: hidden; display: flex; flex-direction: column; }
                        .fi-sidebar-header { flex-shrink: 0; }
                        .fi-sidebar-nav { flex: 1 1 auto; overflow-y: auto; overflow-x: hidden; overscroll-behavior: contain; }
                        .fi-main-ctn { height: 100vh; overflow-y: auto; overflow-x: hidden; overscroll-behavior: contain; }
                        .fi-topbar { position: sticky; top: 0; z-index: 30; }
                    ';

                    return new \Illuminate\Support\HtmlString('
                        <link href="' . $fontUrl . '" rel="stylesheet">
                        <link href="' . asset('assets/css/admin-modern.css') . '" rel="stylesheet">
                        <style>
                            *, html, body { font-family: ' . $fontFamily . ' !important; }
                            ' . $rtlCss . '
                            ' . $scrollFixCss . '
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

            // ── Login page custom header — replaces the plain default
            //    heading/subheading with a proper branded block: eyebrow
            //    badge, big title, short description. Purely additive via
            //    Filament's official hook; the form/Livewire markup is
            //    never touched. The native heading/subheading stay in the
            //    DOM for accessibility but are visually hidden by CSS below.
            ->renderHook(
                'panels::auth.login.form.before',
                function () {
                    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
                    $siteLogo = \App\Models\Setting::get('site_logo');

                    $logoHtml = $siteLogo
                        ? '<img src="' . asset($siteLogo) . '" alt="' . e($siteName) . '" class="admin-login-logo-img">'
                        : '<span class="admin-login-logo-fallback">' . mb_strtoupper(mb_substr(trim($siteName), 0, 2)) . '</span>';

                    return new \Illuminate\Support\HtmlString('
                        <div class="admin-login-header">
                            <div class="admin-login-logo">' . $logoHtml . '</div>
                            <span class="admin-login-eyebrow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13">
                                    <path d="M12 2 3 7v6c0 5 4 9 9 9s9-4 9-9V7l-9-5z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                                ' . __('admin.admin_panel') . '
                            </span>
                            <h1 class="admin-login-title">' . e($siteName) . '</h1>
                            <p class="admin-login-desc">' . __('admin.admin_login_subheading') . '</p>
                        </div>
                    ');
                }
            )

            // ── Login page look & feel — brand gradient background, stone
            //    texture, accented card. Scoped entirely to .fi-simple-layout
            //    (only present on auth pages), so it can never affect the
            //    rest of the admin panel.
            ->renderHook(
                'panels::head.end',
                function () {
                    return new \Illuminate\Support\HtmlString('
                        <style>
                            html:has(.fi-simple-layout), body:has(.fi-simple-layout) {
                                background: #0b2147 !important;
                                overflow: auto !important;
                                height: auto !important;
                                min-height: 100vh !important;
                            }
                            .fi-simple-layout {
                                min-height: 100vh !important;
                                position: relative !important;
                                display: flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                padding: 1rem !important;
                                background:
                                    radial-gradient(circle at 15% 20%, rgba(255,90,31,.16), transparent 40%),
                                    radial-gradient(circle at 85% 80%, rgba(255,90,31,.10), transparent 45%),
                                    repeating-linear-gradient(135deg, rgba(255,255,255,.025) 0 2px, transparent 2px 26px),
                                    linear-gradient(160deg, #0b2147 0%, #123a7a 55%, #0b2147 100%) !important;
                                background-color: #0b2147 !important;
                            }
                            .fi-simple-layout::before {
                                content: "";
                                position: absolute;
                                inset: 0;
                                background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'120\' height=\'120\'%3E%3Cpath d=\'M0 60h120M60 0v120\' stroke=\'%23ffffff\' stroke-opacity=\'0.035\'/%3E%3C/svg%3E") !important;
                                pointer-events: none;
                            }

                            /* Native Filament logo/heading/subheading stay in the DOM
                               for accessibility & <title>, but the custom header block
                               below fully replaces them visually. */
                            .fi-simple-layout .fi-logo,
                            .fi-simple-layout .fi-simple-header-heading,
                            .fi-simple-layout .fi-simple-header-subheading {
                                position: absolute !important;
                                width: 1px !important; height: 1px !important;
                                padding: 0 !important; margin: -1px !important;
                                overflow: hidden !important; clip: rect(0,0,0,0) !important;
                                white-space: nowrap !important; border: 0 !important;
                            }
                            .fi-simple-layout .fi-simple-header {
                                padding: 0 !important;
                                margin: 0 !important;
                                min-height: 0 !important;
                                gap: 0 !important;
                            }

                            /* ── Custom header (compact) ───────────────────── */
                            .admin-login-header {
                                position: relative;
                                z-index: 1;
                                text-align: center;
                                padding: .9rem 2rem .65rem;
                                background: linear-gradient(180deg, #0b2147 0%, #123a7a 100%);
                            }
                            .admin-login-logo {
                                width: 36px; height: 36px;
                                margin: 0 auto .4rem;
                                border-radius: 10px;
                                background: rgba(255,255,255,.08);
                                border: 1px solid rgba(255,255,255,.14);
                                display: flex; align-items: center; justify-content: center;
                                box-shadow: 0 8px 20px -8px rgba(0,0,0,.5);
                            }
                            .admin-login-logo-img { max-width: 24px; max-height: 24px; object-fit: contain; }
                            .admin-login-logo-fallback {
                                font-size: .8rem; font-weight: 800; color: #fff; letter-spacing: .02em;
                            }
                            .admin-login-eyebrow {
                                display: inline-flex; align-items: center; gap: .3rem;
                                background: rgba(255,90,31,.16);
                                border: 1px solid rgba(255,90,31,.4);
                                color: #ff8a3d;
                                font-size: .64rem; font-weight: 700;
                                padding: .2rem .6rem;
                                border-radius: 999px;
                                margin-bottom: .35rem;
                            }
                            .admin-login-title {
                                color: #fff;
                                font-size: 1.05rem;
                                font-weight: 800;
                                margin: 0 0 .15rem;
                                line-height: 1.25;
                            }
                            .admin-login-desc {
                                color: rgba(255,255,255,.65);
                                font-size: .72rem;
                                margin: 0;
                            }

                            /* ── Card ───────────────────────────────────────── */
                            .fi-simple-main {
                                position: relative !important;
                                z-index: 1 !important;
                                border-radius: 1.1rem !important;
                                box-shadow: 0 24px 60px -20px rgba(0,0,0,.55) !important;
                                border: 1px solid rgba(255,255,255,.08) !important;
                                overflow: hidden !important;
                                background: #fff !important;
                                padding: 0 !important;
                                max-width: 30rem !important;
                                max-height: calc(100vh - 2rem) !important;
                                overflow-y: auto !important;
                            }
                            .fi-simple-main form {
                                padding: .8rem 2.2rem 1.2rem !important;
                            }
                            .fi-simple-main .fi-fo-field-wrp {
                                margin-bottom: .1rem !important;
                            }
                            .fi-simple-main label {
                                margin-bottom: .15rem !important;
                                font-size: .82rem !important;
                            }
                            .fi-simple-main .fi-input,
                            .fi-simple-main input[type="email"],
                            .fi-simple-main input[type="password"] {
                                padding-top: .5rem !important;
                                padding-bottom: .5rem !important;
                            }
                            .fi-simple-main .fi-form-actions {
                                margin-top: .5rem !important;
                            }
                        </style>
                    ');
                }
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
