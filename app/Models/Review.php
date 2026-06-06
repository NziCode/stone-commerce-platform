<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id',
        'reviewer_name', 'reviewer_email', 'reviewer_country', 'reviewer_company',
        'rating', 'comment', 'status',
        'approved_by', 'approved_at',
    ];

    protected $casts = [
        'rating'      => 'integer',
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeApproved($q)  { return $q->where('status', 'approved'); }
    public function scopePending($q)   { return $q->where('status', 'pending'); }

    // ── Helpers ────────────────────────────────────────
    public function approve(int $adminId): void
    {
        $this->update([
            'status'      => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now(),
        ]);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }
}
