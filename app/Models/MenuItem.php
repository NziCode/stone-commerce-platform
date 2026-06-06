<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'menu_id', 'parent_id', 'label',
        'url', 'route_name', 'route_params',
        'target', 'icon', 'css_class',
        'is_active', 'sort_order',
    ];

    public array $translatable = ['label'];

    protected $casts = [
        'is_active'    => 'boolean',
        'route_params' => 'array',
    ];

    // ── Relations ──────────────────────────────────────
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeRoots($q)
    {
        return $q->whereNull('parent_id');
    }

    // ── Accessors ──────────────────────────────────────
    public function getHrefAttribute(): string
    {
        if ($this->url) return $this->url;
        if ($this->route_name) {
            try {
                return route($this->route_name, $this->route_params ?? []);
            } catch (\Exception $e) {
                return '#';
            }
        }
        return '#';
    }

    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->exists();
    }
}
