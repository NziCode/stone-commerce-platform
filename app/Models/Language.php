<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'native_name', 'code', 'locale',
        'direction', 'flag', 'is_default', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeDefault($q)
    {
        return $q->where('is_default', true);
    }

    public function scopeRtl($q)
    {
        return $q->where('direction', 'rtl');
    }

    // ── Accessors ──────────────────────────────────────
    public function getIsRtlAttribute(): bool
    {
        return $this->direction === 'rtl';
    }

    // ── Static Helpers ─────────────────────────────────
    public static function allActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::rememberForever('languages.active', function () {
            return static::active()->get();
        });
    }

    public static function getDefault(): ?self
    {
        return Cache::rememberForever('language.default', function () {
            return static::where('is_default', true)->first();
        });
    }

    public static function findByCode(string $code): ?self
    {
        return static::allActive()->firstWhere('code', $code);
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('languages.active');
            Cache::forget('language.default');
        });
        static::deleted(function () {
            Cache::forget('languages.active');
            Cache::forget('language.default');
        });
    }
}
