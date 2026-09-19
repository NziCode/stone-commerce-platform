<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;

class SeoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            static::applyDefaults();
        } catch (QueryException $e) {
            // The settings table doesn't exist yet (fresh install, `migrate:fresh`, test database):
            // there is nothing to apply, and booting must not stop `php artisan migrate` from creating it.
        }
    }

    /**
     * Set the default SEO tags from Settings for the current locale. Providers boot
     * before route middleware runs, so `app()->getLocale()` here would only ever see
     * the app's config default locale — SetLocale middleware calls this again right
     * after it resolves the request's actual locale (from the /fa, /en, ... segment).
     */
    public static function applyDefaults(): void
    {
        $siteName = Setting::get('site_name', config('app.name'));

        SEOMeta::setTitleSeparator(' | ');
        SEOMeta::setTitleDefault($siteName);

        $defaultTitle       = Setting::get('meta_title', $siteName);
        $defaultDescription = Setting::get('meta_description', '');

        // $defaultTitle (the "Meta Title" setting) is a complete title composed by the admin —
        // it already includes the site name, so don't let SEOMeta append it a second time.
        SEOMeta::setTitle($defaultTitle, false);
        SEOMeta::setDescription($defaultDescription);
        SEOMeta::addMeta('robots', 'index,follow');

        OpenGraph::setTitle($defaultTitle);
        OpenGraph::setDescription($defaultDescription);
        OpenGraph::setSiteName($siteName);
        OpenGraph::setType('website');

        JsonLd::setType('Organization');
        JsonLd::setTitle($siteName);
        JsonLd::addValue('url', config('app.url'));

        if ($phone = Setting::get('site_phone')) {
            JsonLd::addValue('telephone', display_phone($phone));
        }
        if ($email = Setting::get('site_email')) {
            JsonLd::addValue('email', $email);
        }
    }
}
