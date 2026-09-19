<?php

namespace App\Models;

use App\Support\WhatsApp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ReservationRequest extends Model
{
    /** Days the customer gets to pay the rest once the prepayment is in (admin can change it per reservation). */
    public const DEFAULT_FINAL_PAYMENT_DAYS = 14;

    protected $fillable = [
        'product_id', 'user_id',
        'name', 'phone_country', 'phone', 'contact_method', 'note',
        'status', 'expires_at', 'approved_at', 'approved_by', 'admin_note',
        'deposit_amount', 'deposit_currency', 'deposit_received_at', 'final_paid_at',
    ];

    protected $casts = [
        'expires_at'          => 'datetime',
        'approved_at'         => 'datetime',
        'deposit_amount'      => 'decimal:2',
        'deposit_received_at' => 'datetime',
        'final_paid_at'       => 'datetime',
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

    // Still holding the product — approved and not past its expiry yet, or prepaid and
    // waiting for the final payment (a prepaid stone is never released automatically).
    public function scopeActiveHold($q)
    {
        return $q->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now())
                    ->orWhere(function ($q) {
                        $q->whereNotNull('deposit_received_at')->whereNull('final_paid_at');
                    });
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

    /** "+989123456789" — for tel: links (a typed leading 0 or "00" is handled). */
    public function getCallNumberAttribute(): ?string
    {
        $digits = WhatsApp::customerDigits($this->phone_country, $this->phone);

        return $digits ? '+' . $digits : null;
    }

    /**
     * Chat link to the customer with a ready-to-edit opening message (Persian for Iranian
     * numbers, English otherwise). Null when the number can't be turned into a wa.me link.
     */
    public function getWhatsappUrlAttribute(): ?string
    {
        $product = $this->product;
        $name    = $product ? (string) $product->getTranslation('name', 'fa', false) : '';
        $name    = $name !== '' ? $name : (string) ($product?->getTranslation('name', 'en', false) ?: $product?->sku);
        $code    = $product?->sku ? " ({$product->sku})" : '';
        $iran    = trim((string) $this->phone_country) === '+98';

        $message = $iran
            ? "سلام، درخواست رزرو شما برای سنگ «{$name}»{$code} در گروه تجاری EN را دریافت کردیم. لطفاً هماهنگی‌های بعدی را اینجا انجام دهیم."
            : "Hello, we received your reservation request for “{$name}”{$code} at EN Trading Group. Let's continue the details here.";

        return WhatsApp::chatUrl($this->phone_country, $this->phone, $message);
    }

    // ── Sales stage ────────────────────────────────────
    // Derived from the status plus the payment columns, so the status enum stays as it was:
    // pending → awaiting_deposit → deposit_paid → completed  (or rejected / expired / cancelled)
    public function getStageAttribute(): string
    {
        if ($this->status !== 'approved') {
            return (string) $this->status;
        }

        return match (true) {
            $this->final_paid_at !== null      => 'completed',
            $this->deposit_received_at !== null => 'deposit_paid',
            default                            => 'awaiting_deposit',
        };
    }

    public function getStageLabelAttribute(): string
    {
        return match ($this->stage) {
            'awaiting_deposit' => __('admin.reservation_stage_awaiting_deposit'),
            'deposit_paid'     => __('admin.reservation_stage_deposit_paid'),
            'completed'        => __('admin.reservation_stage_completed'),
            default            => $this->status_label,
        };
    }

    public function getStageColorAttribute(): string
    {
        return match ($this->stage) {
            'awaiting_deposit' => 'info',
            'deposit_paid'     => 'primary',
            'completed'        => 'success',
            default            => $this->status_color,
        };
    }

    public function hasDeposit(): bool
    {
        return $this->deposit_received_at !== null;
    }

    /** Prepaid, the final payment deadline has passed — the stone stays held until an admin decides. */
    public function isFinalPaymentOverdue(): bool
    {
        return $this->stage === 'deposit_paid' && $this->expires_at && $this->expires_at->isPast();
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
     *
     * Returns false without changing anything if the product is no longer
     * available — closes the race where two admins approve two pending
     * requests for the same product in quick succession.
     */
    public function approve(?int $approvedByUserId = null): bool
    {
        if (!$this->product || !$this->product->tryReserve()) {
            return false;
        }

        $days  = (int) (\App\Models\Setting::get('reservation_duration_days', 3));
        $hours = (int) (\App\Models\Setting::get('reservation_duration_hours', 0));

        $expiresAt = now()->addDays($days)->addHours($hours);

        $this->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedByUserId,
            'expires_at'  => $expiresAt,
        ]);

        return true;
    }

    public function reject(?string $adminNote = null): void
    {
        $this->update([
            'status'     => 'rejected',
            'admin_note' => $adminNote ?? $this->admin_note,
        ]);
    }

    /**
     * Record the prepayment. The stone stays reserved; `expires_at` becomes the deadline
     * for the final payment (null = no deadline) and the reservation is no longer
     * released automatically once that deadline passes.
     */
    public function markDepositReceived(?float $amount = null, ?string $currency = null, ?Carbon $finalDeadline = null, ?string $note = null): void
    {
        $adminNote = $this->admin_note;

        if (filled($note)) {
            $adminNote = trim(($adminNote ? $adminNote . "\n" : '') . $note);
        }

        $this->update([
            'deposit_amount'      => $amount,
            'deposit_currency'    => $currency,
            'deposit_received_at' => now(),
            'expires_at'          => $finalDeadline,
            'admin_note'          => $adminNote,
        ]);
    }

    /** The rest is paid: the stone is sold and the reservation stops expiring. */
    public function markFinalPaid(): void
    {
        DB::transaction(function () {
            $this->update(['final_paid_at' => now(), 'expires_at' => null]);
            $this->product?->markAsSold();
        });
    }

    /**
     * Manually release an approved reservation before it naturally expires.
     * A prepaid reservation is marked cancelled instead of expired (the deposit
     * still has to be settled with the customer outside the system).
     */
    public function release(): void
    {
        $this->update(['status' => $this->hasDeposit() ? 'cancelled' : 'expired']);

        if ($this->product && $this->product->isReserved()) {
            $this->product->markAsAvailable();
        }
    }
}
