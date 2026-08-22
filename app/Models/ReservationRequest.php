<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationRequest extends Model
{
    protected $fillable = [
        'product_id', 'user_id',
        'name', 'phone_country', 'phone', 'contact_method', 'note',
        'status', 'expires_at', 'approved_at', 'approved_by', 'admin_note',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'approved_at' => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopePending($q)
    {
        return $q->where('status', 'pending');
    }

    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }

    // Still holding the product — approved and not past its expiry yet.
    public function scopeActiveHold($q)
    {
        return $q->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    // ── Helpers ────────────────────────────────────────
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isExpired(): bool  { return $this->status === 'expired'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function getFullPhoneAttribute(): string
    {
        return trim(($this->phone_country ?? '') . ' ' . $this->phone);
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if ($this->contact_method !== 'whatsapp') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $this->full_phone);
        return $digits ? 'https://wa.me/' . $digits : null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => __('admin.reservation_status_pending'),
            'approved'  => __('admin.reservation_status_approved'),
            'rejected'  => __('admin.reservation_status_rejected'),
            'expired'   => __('admin.reservation_status_expired'),
            'cancelled' => __('admin.reservation_status_cancelled'),
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'warning',
            'approved'  => 'success',
            'rejected'  => 'danger',
            'expired'   => 'gray',
            'cancelled' => 'gray',
            default     => 'gray',
        };
    }

    /**
     * Approve the request: locks the product as 'reserved' for the duration
     * configured in Settings (reservation_duration_days / reservation_duration_hours).
     */
    public function approve(?int $approvedByUserId = null): void
    {
        $days  = (int) (\App\Models\Setting::get('reservation_duration_days', 3));
        $hours = (int) (\App\Models\Setting::get('reservation_duration_hours', 0));

        $expiresAt = now()->addDays($days)->addHours($hours);

        $this->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedByUserId,
            'expires_at'  => $expiresAt,
        ]);

        $this->product?->markAsReserved();
    }

    public function reject(?string $adminNote = null): void
    {
        $this->update([
            'status'     => 'rejected',
            'admin_note' => $adminNote ?? $this->admin_note,
        ]);
    }

    /**
     * Manually release an approved reservation before it naturally expires.
     */
    public function release(): void
    {
        $this->update(['status' => 'expired']);

        if ($this->product && $this->product->isReserved()) {
            $this->product->markAsAvailable();
        }
    }
}
