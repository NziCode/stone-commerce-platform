<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Providers\SeoServiceProvider;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin*')) {
            return $next($request);
        }

        $segment = $request->segment(1);

        try {
            $validLocales = Language::allActive()->pluck('code')->toArray();
        } catch (\Exception $e) {
            $validLocales = ['fa', 'en', 'hi', 'it', 'ar', 'zh', 'tr'];
        }

        if (in_array($segment, $validLocales)) {
            app()->setLocale($segment);
            session(['locale' => $segment]);
        } else {
            $locale = session('locale', 'fa');
            app()->setLocale($locale);
        }

        // Re-apply Setting-derived SEO defaults now that the request's real locale is
        // known — SeoServiceProvider::boot() ran too early to see it (providers boot
        // before middleware).
        SeoServiceProvider::applyDefaults();

        return $next($request);
    }
}
