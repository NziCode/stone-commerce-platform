<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale');

        if (!$locale) {
            $locale = Language::getDefault()?->code ?? 'fa';
        }

        // چک کن زبان فعال باشه
        $validLocale = Language::allActive()->firstWhere('code', $locale);
        if (!$validLocale) {
            $locale = Language::getDefault()?->code ?? 'fa';
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }
}
