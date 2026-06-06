<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'location',
        'city', 'country',
        'meta_title', 'meta_description', 'og_image',
        'starts_at', 'ends_at', 'status',
        'website_url', 'booth_number', 'hall_number', 'views_count',
    ];

    public array $translatable = [
        'title', 'slug', 'description', 'location',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'ends_at'     => 'datetime',
        'views_count' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(600)->height(400)
            ->performOnCollections('cover', 'gallery');

        $this->addMediaConversion('medium')
            ->width(1200)->height(800)
            ->performOnCollections('cover');
    }

    // ── Relations ──────────────────────────────────────
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeUpcoming($q)
    {
        return $q->where('status', 'upcoming')
            ->where('starts_at', '>', now())
            ->orderBy('starts_at');
    }

    public function scopeOngoing($q)
    {
        return $q->where('status', 'ongoing');
    }

    public function scopeFinished($q)
    {
        return $q->where('status', 'finished')
            ->orderBy('ends_at', 'desc');
    }

    public function scopeActive($q)
    {
        return $q->whereIn('status', ['upcoming', 'ongoing']);
    }

    // ── Accessors ──────────────────────────────────────
    public function getCoverUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover', 'medium')
            ?: asset('images/default-event.jpg');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'upcoming'  => 'آینده',
            'ongoing'   => 'در حال برگزاری',
            'finished'  => 'پایان یافته',
            'cancelled' => 'لغو شده',
            default     => $this->status,
        };
    }

    public function getDurationAttribute(): string
    {
        if (!$this->starts_at || !$this->ends_at) return '';
        return $this->starts_at->format('Y/m/d') . ' تا ' . $this->ends_at->format('Y/m/d');
    }

    public function getIsUpcomingAttribute(): bool { return $this->status === 'upcoming'; }
    public function getIsOngoingAttribute(): bool  { return $this->status === 'ongoing'; }
    public function getIsFinishedAttribute(): bool { return $this->status === 'finished'; }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
