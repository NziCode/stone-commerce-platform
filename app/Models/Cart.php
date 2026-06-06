<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'session_id', 'coupon_code',
        'discount_amount', 'currency', 'expires_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'expires_at'      => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    // ── Accessors ──────────────────────────────────────
    public function getSubtotalAttribute(): float
    {
        return $this->items->sum('price');
    }

    public function getTotalAttribute(): float
    {
        return max(0, $this->subtotal - $this->discount_amount);
    }

    public function getItemsCountAttribute(): int
    {
        return $this->items->count();
    }

    public function getIsEmptyAttribute(): bool
    {
        return $this->items->isEmpty();
    }

    // ── Helpers ────────────────────────────────────────
    public function hasProduct(int $productId): bool
    {
        return $this->items()->where('product_id', $productId)->exists();
    }

    public function addProduct(Product $product): CartItem
    {
        return $this->items()->firstOrCreate(
            ['product_id' => $product->id],
            ['price' => $product->price, 'currency' => $this->currency]
        );
    }

    public function removeProduct(int $productId): void
    {
        $this->items()->where('product_id', $productId)->delete();
    }

    public function clear(): void
    {
        $this->items()->delete();
        $this->update(['coupon_code' => null, 'discount_amount' => 0]);
    }
}
