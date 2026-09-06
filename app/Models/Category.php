<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, NodeTrait, HasTranslations, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'excerpt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_active',
        'sort_order',
        'icon_color',
    ];

    public array $translatable = [
        'name', 'slug', 'description', 'excerpt',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Media ──────────────────────────────────────────
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)->height(300)->sharpen(10)
            ->nonOptimized()
            ->performOnCollections('image', 'gallery');

        $this->addMediaConversion('medium')
            ->width(800)->height(600)
            ->nonOptimized()
            ->performOnCollections('image', 'gallery');
    }

    // ── Relations ──────────────────────────────────────
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_category')
            ->withPivot('is_primary');
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeRoots($q)
    {
        return $q->whereIsRoot();
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('_lft');
    }

    // ── Accessors ──────────────────────────────────────
    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('image', 'medium')
            ?: asset('images/default-category.jpg');
    }

    public function getThumbUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('image', 'thumb')
            ?: asset('images/default-category.jpg');
    }

    public function getSlugForLocale(string $locale): string
    {
        return $this->getTranslation('slug', $locale, false) ?? '';
    }

    public function getBreadcrumbAttribute(): array
    {
        return $this->ancestorsAndSelf()
            ->get()
            ->map(fn($cat) => [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'id'   => $cat->id,
            ])
            ->toArray();
    }
}
