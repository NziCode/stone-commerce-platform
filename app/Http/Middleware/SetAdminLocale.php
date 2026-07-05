<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetAdminLocale
{
    const ADMIN_LOCALES = ['fa', 'en', 'ar', 'hi', 'it', 'zh', 'tr'];

    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('admin_locale');

        if (!$locale && auth()->check()) {
            $locale = auth()->user()->preferred_admin_locale
                ?? auth()->user()->locale
                ?? null;
        }

        if (!$locale || !in_array($locale, self::ADMIN_LOCALES)) {
            $locale = 'fa';
        }

        App::setLocale($locale);
        Session::put('admin_locale', $locale);

        return $next($request);
    }
}
