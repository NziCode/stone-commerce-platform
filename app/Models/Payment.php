<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'order_id', 'type', 'status',
        'gateway', 'transaction_id', 'reference_id', 'gateway_response',
        'amount', 'currency',
        'receipt_file', 'bank_name', 'bank_country',
        'transfer_reference', 'receipt_notes', 'receipt_date',
        'verified_by', 'verified_at', 'admin_notes',
        'paid_at',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'receipt_date'     => 'date',
        'verified_at'      => 'datetime',
        'paid_at'          => 'datetime',
        'gateway_response' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('receipt')->singleFile();
    }

    // ── Relations ──────────────────────────────────────
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopePaid($q)    { return $q->where('status', 'paid'); }
    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeFailed($q)  { return $q->where('status', 'failed'); }

    public function scopeOnline($q)  { return $q->where('type', 'online'); }
    public function scopeReceipt($q) { return $q->where('type', 'receipt'); }

    public function scopeAwaitingVerification($q)
    {
        return $q->where('type', 'receipt')->where('status', 'pending');
    }

    // ── Accessors ──────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'در انتظار',
            'paid'      => 'پرداخت شده',
            'failed'    => 'ناموفق',
            'cancelled' => 'لغو شده',
            'refunded'  => 'مسترد شده',
            default     => $this->status,
        };
    }

    public function getReceiptFileUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('receipt') ?: null;
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount) . ' ' . $this->currency;
    }

    // ── Helpers ────────────────────────────────────────
    public function isPaid(): bool    { return $this->status === 'paid'; }
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isOnline(): bool  { return $this->type === 'online'; }
    public function isReceipt(): bool { return $this->type === 'receipt'; }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid', 'paid_at' => now()]);
        $this->order->confirm();
    }

    public function verify(int $adminId, string $notes = ''): void
    {
        $this->update([
            'verified_by'  => $adminId,
            'verified_at'  => now(),
            'admin_notes'  => $notes,
        ]);
        $this->markAsPaid();
    }

    public function fail(array $response = []): void
    {
        $this->update([
            'status'           => 'failed',
            'gateway_response' => $response,
        ]);
    }
}
