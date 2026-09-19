<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use HasFactory, HasTranslations, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'template',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image',
        'is_active', 'views_count',
    ];

    public array $translatable = [
        'title', 'slug', 'content', 'excerpt',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'content'     => 'array',
        'is_active'   => 'boolean',
        'views_count' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(800)->height(450)
            ->nonOptimized()
            ->performOnCollections('cover', 'gallery');

        // Web-sized versions so a 6 MB camera original never reaches a visitor's phone:
        // 'hero' = full-width banner, 'card' = collage/gallery photo, 'portrait' = square face crop.
        $this->addMediaConversion('hero')
            ->width(1920)
            ->nonOptimized()
            ->performOnCollections('cover');

        $this->addMediaConversion('card')
            ->width(1000)
            ->nonOptimized()
            ->performOnCollections('gallery');

        $this->addMediaConversion('portrait')
            ->fit(Fit::Crop, 800, 800)
            ->nonOptimized()
            ->performOnCollections('cover');
    }

    /**
     * URL of a media item in the given conversion, or of the original while that
     * conversion hasn't been generated yet (media uploaded before it existed).
     */
    public static function mediaUrl(?Media $media, string $conversion): ?string
    {
        if (! $media) {
            return null;
        }

        return $media->hasGeneratedConversion($conversion) ? $media->getUrl($conversion) : $media->getUrl();
    }

    public function coverUrlFor(string $conversion): ?string
    {
        return static::mediaUrl($this->getFirstMedia('cover'), $conversion);
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeTemplate($q, string $template)
    {
        return $q->where('template', $template);
    }

    public function scopeFindBySlug($q, string $slug, string $locale = 'fa')
    {
        return $q->whereJsonContains("slug->{$locale}", $slug);
    }

    // ── Accessors ──────────────────────────────────────
    public function getCoverUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover', 'thumb')
            ?: asset('images/default-page.jpg');
    }

    // ── Helpers ────────────────────────────────────────
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
