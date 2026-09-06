<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'is_public'];

    protected $casts = ['is_public' => 'boolean'];

    const CACHE_KEY = 'site_settings_all';

    /**
     * Keys whose `value` is a JSON object keyed by locale code (e.g. {"fa":"...","en":"..."})
     * rather than a plain scalar. Shared by the settings migration, ManageSettings admin page,
     * and SettingSeeder so there's one place that defines which settings are translatable.
     */
    const TRANSLATABLE_KEYS = [
        'site_name', 'site_tagline', 'site_working_hours', 'site_address',
        'meta_title', 'meta_description',
        'payment_receipt_bank_name', 'payment_receipt_instructions',
        'sms_otp_template', 'sms_order_confirmed_template', 'sms_order_shipped_template',
        'about_title', 'about_desc', 'about_feature_1', 'about_feature_2', 'about_feature_3',
        'hero_eyebrow', 'hero_title', 'hero_desc', 'hero_search_keywords',
    ];

    // ── Static Helpers ─────────────────────────────────
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = Cache::rememberForever(self::CACHE_KEY, fn() =>
        static::all()->pluck('value', 'key')->toArray()
        );

        $raw = $all[$key] ?? null;

        if ($raw === null) {
            return $default;
        }

        if (! in_array($key, self::TRANSLATABLE_KEYS, true)) {
            return $raw;
        }

        return static::resolveTranslatable($raw, $default);
    }

    /**
     * Decode a locale-keyed JSON value and pick the best match for the current request:
     * active locale → site default locale → English → first non-empty entry → $default.
     */
    protected static function resolveTranslatable(string $raw, mixed $default): mixed
    {
        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            // Legacy plain value that hasn't been migrated yet — return as-is rather than lose it.
            return $raw !== '' ? $raw : $default;
        }

        $locale = app()->getLocale();
        $defaultLocale = \App\Services\LanguageService::getDefault()?->code ?? 'fa';

        foreach ([$locale, $defaultLocale, 'en'] as $candidate) {
            if (! empty($decoded[$candidate])) {
                return $decoded[$candidate];
            }
        }

        foreach ($decoded as $value) {
            if (! empty($value)) {
                return $value;
            }
        }

        return $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
        Cache::forget(self::CACHE_KEY);
    }

    public static function group(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    public static function publicSettings(): array
    {
        return Cache::rememberForever('site_settings_public', fn() =>
        static::where('is_public', true)->pluck('value', 'key')->toArray()
        );
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
            Cache::forget('site_settings_public');
        });
        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
            Cache::forget('site_settings_public');
        });
    }
}
