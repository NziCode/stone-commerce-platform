<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'title', 'subtitle', 'button_text', 'button_link', 'button_target',
        'image', 'mobile_image', 'video', 'type',
        'overlay_color', 'overlay_opacity',
        'is_active', 'sort_order',
    ];

    public array $translatable = ['title', 'subtitle', 'button_text'];

    protected $casts = [
        'is_active'       => 'boolean',
        'overlay_opacity' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('mobile_image')->singleFile();
        $this->addMediaCollection('video')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
            ->width(1920)->height(800)
            ->performOnCollections('image');

        $this->addMediaConversion('mobile')
            ->width(768)->height(500)
            ->performOnCollections('mobile_image', 'image');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }

    // ── Accessors ──────────────────────────────────────
    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('image', 'optimized')
            ?: ($this->image ? asset($this->image) : '');
    }

    public function getMobileImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('mobile_image', 'mobile')
            ?: $this->image_url;
    }

    public function getIsVideoAttribute(): bool
    {
        return $this->type === 'video';
    }
}
