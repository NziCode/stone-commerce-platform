<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    // ── Relations ──────────────────────────────────────
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeLocation($q, string $location)
    {
        return $q->where('location', $location);
    }

    // ── Static Helpers ─────────────────────────────────
    public static function getByLocation(string $location): ?self
    {
        return Cache::rememberForever("menu.{$location}", function () use ($location) {
            return static::with(['items' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with(['children' => function ($q) {
                        $q->where('is_active', true)->orderBy('sort_order');
                    }]);
            }])
                ->where('location', $location)
                ->where('is_active', true)
                ->first();
        });
    }

    protected static function booted(): void
    {
        static::saved(fn($m)   => Cache::forget("menu.{$m->location}"));
        static::deleted(fn($m) => Cache::forget("menu.{$m->location}"));
    }
}
