<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;

class SeoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $locale = app()->getLocale();

        $siteName = Setting::get('site_name', config('app.name'));

        SEOMeta::setTitleSeparator(' | ');
        SEOMeta::setTitleDefault($siteName);

        $defaultTitle       = Setting::get("meta_title_{$locale}") ?? Setting::get('meta_title_fa') ?? $siteName;
        $defaultDescription = Setting::get("meta_description_{$locale}") ?? Setting::get('meta_description_fa') ?? '';

        SEOMeta::setTitle($defaultTitle);
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
            JsonLd::addValue('telephone', $phone);
        }
        if ($email = Setting::get('site_email')) {
            JsonLd::addValue('email', $email);
        }
    }
}
