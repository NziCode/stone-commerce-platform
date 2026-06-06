<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'name', 'country', 'language',
        'is_active', 'token', 'confirmed_at', 'unsubscribed_at',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'confirmed_at'     => 'datetime',
        'unsubscribed_at'  => 'datetime',
    ];

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)        { return $q->where('is_active', true); }
    public function scopeConfirmed($q)     { return $q->whereNotNull('confirmed_at'); }
    public function scopeByLanguage($q, string $lang) { return $q->where('language', $lang); }

    // ── Helpers ────────────────────────────────────────
    public function unsubscribe(): void
    {
        $this->update(['is_active' => false, 'unsubscribed_at' => now()]);
    }

    public function confirm(): void
    {
        $this->update(['confirmed_at' => now(), 'is_active' => true]);
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(64);
            }
        });
    }
}
