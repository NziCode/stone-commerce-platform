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

class Post extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'slug', 'excerpt', 'content',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image',
        'reading_time', 'status', 'published_at', 'views_count',
    ];

    public array $translatable = [
        'title', 'slug', 'excerpt', 'content',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count'  => 'integer',
        'reading_time' => 'integer',
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
            ->nonOptimized()
            ->performOnCollections('cover', 'gallery');

        $this->addMediaConversion('medium')
            ->width(1200)->height(800)
            ->nonOptimized()
            ->performOnCollections('cover');
    }

    // ── Relations ──────────────────────────────────────
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopePublished($q)
    {
        return $q->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($q)
    {
        return $q->where('status', 'draft');
    }

    public function scopeLatestPublished($q)
    {
        return $q->published()->orderBy('published_at', 'desc');
    }

    public function scopeFindBySlug($q, string $slug, string $locale = 'fa')
    {
        return $q->whereJsonContains("slug->{$locale}", $slug);
    }

    // ── Accessors ──────────────────────────────────────
    public function getCoverUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover', 'medium')
            ?: asset('images/default-post.jpg');
    }

    public function getThumbUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover', 'thumb')
            ?: asset('images/default-post.jpg');
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' && $this->published_at?->isPast();
    }

    public function getReadingTimeTextAttribute(): string
    {
        return $this->reading_time ? "{$this->reading_time} دقیقه مطالعه" : '';
    }

    // ── Helpers ────────────────────────────────────────
    public function publish(): void
    {
        $this->update([
            'status'       => 'published',
            'published_at' => $this->published_at ?? now(),
        ]);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
