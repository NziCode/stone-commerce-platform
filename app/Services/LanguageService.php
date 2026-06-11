<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageService
{
    public static function getActive(): \Illuminate\Support\Collection
    {
        return Cache::remember('languages.active', 86400, function () {
            return Language::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public static function getDefault(): ?Language
    {
        return Cache::remember('languages.default', 86400, function () {
            return Language::where('is_default', true)->first();
        });
    }

    public static function setLocale(string $locale): void
    {
        $lang = static::getActive()->firstWhere('code', $locale);

        if (!$lang) return;

        App::setLocale($locale);
        Session::put('locale', $locale);
        Session::put('direction', $lang->direction);
    }

    public static function current(): ?Language
    {
        $locale = App::getLocale();
        return static::getActive()->firstWhere('code', $locale);
    }

    public static function isRtl(): bool
    {
        $lang = static::current();
        return $lang && $lang->direction === 'rtl';
    }

    public static function clearCache(): void
    {
        Cache::forget('languages.active');
        Cache::forget('languages.default');
    }

    public static function getLocales(): array
    {
        return static::getActive()->pluck('code')->toArray();
    }
}
