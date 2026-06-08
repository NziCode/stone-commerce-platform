<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Language;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $segment = $request->segment(1);

        try {
            $validLocales = Language::allActive()->pluck('code')->toArray();
        } catch (\Exception $e) {
            $validLocales = ['fa', 'en', 'hi', 'it', 'ar'];
        }

        if (in_array($segment, $validLocales)) {
            app()->setLocale($segment);
            session(['locale' => $segment]);
        } else {
            $locale = session('locale', 'fa');
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
