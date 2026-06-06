<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id',
        'customer_name', 'customer_email', 'customer_phone',
        'customer_company', 'customer_country', 'customer_address', 'customer_postal_code',
        'status', 'payment_type',
        'coupon_code', 'subtotal', 'discount_amount', 'shipping_cost', 'tax_amount', 'total', 'currency',
        'customer_notes', 'admin_notes',
        'tracking_code', 'shipping_method',
        'confirmed_at', 'shipped_at', 'delivered_at', 'cancelled_at',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost'   => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'total'           => 'decimal:2',
        'confirmed_at'    => 'datetime',
        'shipped_at'      => 'datetime',
        'delivered_at'    => 'datetime',
        'cancelled_at'    => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────
    public function user(): BelongsTo    { return $this->belongsTo(User::class); }

    public function items(): HasMany     { return $this->hasMany(OrderItem::class); }

    public function payments(): HasMany  { return $this->hasMany(Payment::class); }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function paidPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->where('status', 'paid')->latestOfMany();
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopePending($q)    { return $q->where('status', 'pending'); }
    public function scopeConfirmed($q)  { return $q->where('status', 'confirmed'); }
    public function scopeShipped($q)    { return $q->where('status', 'shipped'); }
    public function scopeCancelled($q)  { return $q->where('status', 'cancelled'); }

    public function scopeForUser($q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    public function scopeOnlinePayment($q)
    {
        return $q->where('payment_type', 'online');
    }

    public function scopeReceiptPayment($q)
    {
        return $q->where('payment_type', 'receipt');
    }

    // ── Accessors ──────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'در انتظار پرداخت',
            'processing' => 'در حال بررسی',
            'confirmed'  => 'تأیید شده',
            'shipped'    => 'ارسال شده',
            'delivered'  => 'تحویل داده شده',
            'cancelled'  => 'لغو شده',
            'refunded'   => 'مسترد شده',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'yellow',
            'processing' => 'blue',
            'confirmed'  => 'green',
            'shipped'    => 'indigo',
            'delivered'  => 'emerald',
            'cancelled'  => 'red',
            'refunded'   => 'orange',
            default      => 'gray',
        };
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payments()->where('status', 'paid')->exists();
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total) . ' ' . $this->currency;
    }

    // ── Helpers ────────────────────────────────────────
    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isConfirmed(): bool { return $this->status === 'confirmed'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }
    public function isDelivered(): bool { return $this->status === 'delivered'; }

    public function confirm(): void
    {
        $this->update(['status' => 'confirmed', 'confirmed_at' => now()]);

        // محصول رو sold کن
        foreach ($this->items as $item) {
            $item->product?->markAsSold();
        }
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        // محصول رو دوباره available کن
        foreach ($this->items as $item) {
            $item->product?->markAsAvailable();
        }
    }

    public static function generateOrderNumber(): string
    {
        $count = static::whereDate('created_at', today())->count() + 1;
        return 'ORD-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
