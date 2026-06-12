<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'key', 'label', 'group', 'type', 'options', 'unit',
        'min_value', 'max_value', 'step_value', 'default_value',
        'is_filterable', 'show_in_product_page', 'is_active', 'sort_order',
    ];

    // options is NOT translatable -> stored as plain array of {key, label:{locale:...}}
    public array $translatable = ['label', 'group'];

    protected $casts = [
        'options'              => 'array',
        'min_value'            => 'decimal:4',
        'max_value'            => 'decimal:4',
        'step_value'           => 'decimal:4',
        'is_filterable'        => 'boolean',
        'show_in_product_page' => 'boolean',
        'is_active'            => 'boolean',
        'sort_order'           => 'integer',
    ];

    // ── Relations ──────────────────────────────────────
    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeFilterable($q)
    {
        return $q->where('is_filterable', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order');
    }

    public function scopeSearch($q, string $term)
    {
        return $q->where(function ($query) use ($term) {
            $query->where('key', 'like', "%{$term}%");

            foreach (\App\Services\LanguageService::getLocales() as $locale) {
                $query->orWhereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT(label, '$.{$locale}')) LIKE ?",
                    ["%{$term}%"]
                );
            }
        });
    }

    // ── Helpers ────────────────────────────────────────
    public function isSelect(): bool { return $this->type === 'select'; }
    public function isNumber(): bool { return $this->type === 'number'; }
    public function isBool(): bool   { return $this->type === 'bool'; }
    public function isText(): bool   { return $this->type === 'text'; }

    /**
     * Get options for select type as [key => label] for current locale.
     */
    public function getOptionsForLocale(?string $locale = null): array
    {
        $locale  = $locale ?: app()->getLocale();
        $options = $this->options ?? [];

        $result = [];
        foreach ($options as $option) {
            $key   = $option['key'] ?? null;
            $label = $option['label'][$locale]
                ?? $option['label']['en']
                ?? $key;

            if ($key !== null) {
                $result[$key] = $label;
            }
        }

        return $result;
    }

    public function getUsageCountAttribute(): int
    {
        return $this->productAttributes()->count();
    }

    public function isTranslationComplete(string $field): bool
    {
        $locales = \App\Services\LanguageService::getLocales();

        if ($field === 'options') {
            if (!$this->isSelect()) {
                return true;
            }

            foreach ($this->options ?? [] as $option) {
                foreach ($locales as $locale) {
                    if (blank($option['label'][$locale] ?? null)) {
                        return false;
                    }
                }
            }

            return true;
        }

        $values = $this->getTranslations($field);

        foreach ($locales as $locale) {
            $val = $values[$locale] ?? null;

            if (blank($val)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Distinct existing group labels (current locale) across all attributes.
     */
    public static function getDistinctGroups(): array
    {
        $locale = app()->getLocale();

        return static::query()
            ->whereNotNull('group')
            ->get()
            ->map(fn ($attr) => $attr->getTranslation('group', $locale, false))
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }
}
