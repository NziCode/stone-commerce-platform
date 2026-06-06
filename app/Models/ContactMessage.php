<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'country',
        'subject', 'message', 'status',
        'admin_reply', 'replied_at', 'replied_by',
        'ip_address', 'user_agent',
    ];

    protected $casts = ['replied_at' => 'datetime'];

    // ── Relations ──────────────────────────────────────
    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeNew($q)    { return $q->where('status', 'new'); }
    public function scopeUnread($q) { return $q->whereIn('status', ['new', 'read']); }
    public function scopeReplied($q){ return $q->where('status', 'replied'); }

    // ── Accessors ──────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'new'      => 'جدید',
            'read'     => 'خوانده شده',
            'replied'  => 'پاسخ داده شده',
            'archived' => 'بایگانی',
            default    => $this->status,
        };
    }

    // ── Helpers ────────────────────────────────────────
    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }

    public function reply(string $replyText, int $adminId): void
    {
        $this->update([
            'admin_reply' => $replyText,
            'replied_at'  => now(),
            'replied_by'  => $adminId,
            'status'      => 'replied',
        ]);
    }
}
