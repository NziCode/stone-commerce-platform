<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'is_public'];

    protected $casts = ['is_public' => 'boolean'];

    const CACHE_KEY = 'site_settings_all';

    // ── Static Helpers ─────────────────────────────────
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = Cache::rememberForever(self::CACHE_KEY, fn() =>
        static::all()->pluck('value', 'key')->toArray()
        );
        return $all[$key] ?? $default;
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
