<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
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

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(800)->height(450)
            ->nonOptimized()
            ->performOnCollections('cover', 'gallery');
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
