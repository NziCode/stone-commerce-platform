<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Translation;
use App\Observers\TranslationObserver;
use App\Support\MailSettings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // cPanel-style hosting: the app lives in ~/<app> and the web root is the sibling
        // ~/public_html, so there is no <app>/public folder and public_path() would point
        // nowhere (sitemap.xml, asset versioning, ...). Use the real web root when that is the layout.
        $webRoot = dirname(base_path()) . DIRECTORY_SEPARATOR . 'public_html';

        if (! is_dir(public_path()) && is_dir($webRoot)) {
            $this->app->usePublicPath($webRoot);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Translation::observe(TranslationObserver::class);
        MailSettings::apply();
    }
}
