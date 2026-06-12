<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'attribute_id', 'value', 'sort_order',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    // ── Relations ──────────────────────────────────────
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    // ── Accessors ──────────────────────────────────────
    public function getDisplayValueAttribute(): string
    {
        $attribute = $this->attribute;
        $raw       = $this->value;

        if (!$attribute) {
            return is_array($raw) ? json_encode($raw) : (string) $raw;
        }

        if ($attribute->isNumber()) {
            $val = $raw['value'] ?? $raw;
            return $attribute->unit ? "{$val} {$attribute->unit}" : (string) $val;
        }

        if ($attribute->isBool()) {
            $val = $raw['value'] ?? $raw;
            return $val ? __('admin.yes') : __('admin.no');
        }

        if ($attribute->isSelect()) {
            $optionKey = $raw['value'] ?? $raw;
            $options   = $attribute->getOptionsForLocale();
            $label     = $options[$optionKey] ?? $optionKey;
            return $attribute->unit ? "{$label} {$attribute->unit}" : (string) $label;
        }

        // text -> {"fa": "...", "en": "..."}
        $val = $raw[app()->getLocale()] ?? $raw['en'] ?? '';
        return $attribute->unit ? "{$val} {$attribute->unit}" : (string) $val;
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeFilterable($q)
    {
        return $q->whereHas('attribute', fn ($a) => $a->where('is_filterable', true));
    }
}
