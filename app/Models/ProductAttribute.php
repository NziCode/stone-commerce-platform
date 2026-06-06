<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ProductAttribute extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'product_id', 'key', 'value', 'unit', 'is_filterable', 'sort_order',
    ];

    public array $translatable = ['key', 'value'];

    protected $casts = [
        'is_filterable' => 'boolean',
    ];

    // ── Relations ──────────────────────────────────────
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── Accessors ──────────────────────────────────────
    public function getDisplayValueAttribute(): string
    {
        $val = $this->value;
        return $this->unit ? "{$val} {$this->unit}" : $val;
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeFilterable($q)
    {
        return $q->where('is_filterable', true);
    }
}
