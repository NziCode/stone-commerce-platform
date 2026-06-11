<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Translation extends Model
{
    protected $fillable = [
        'locale', 'group', 'key', 'value', 'is_auto',
    ];

    protected $casts = [
        'is_auto' => 'boolean',
    ];

    // ────────────────────────────────────────────────
    // کش: همه ترجمه‌های یک locale+group
    // ────────────────────────────────────────────────
    public static function getCached(string $locale, string $group): array
    {
        return Cache::rememberForever(
            "translations.{$locale}.{$group}",
            fn () => static::where('locale', $locale)
                ->where('group', $group)
                ->pluck('value', 'key')
                ->toArray()
        );
    }

    public static function clearCache(string $locale = null, string $group = null): void
    {
        if ($locale && $group) {
            Cache::forget("translations.{$locale}.{$group}");
            return;
        }

        // پاک کردن همه کش ترجمه‌ها
        $locales = Language::pluck('code');
        $groups  = static::distinct()->pluck('group');

        foreach ($locales as $loc) {
            foreach ($groups as $grp) {
                Cache::forget("translations.{$loc}.{$grp}");
            }
        }
    }
}
