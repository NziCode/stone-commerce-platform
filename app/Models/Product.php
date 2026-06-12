<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description',
        'sku', 'mine_code', 'origin_country',
        'price', 'price_usd', 'price_eur', 'price_on_request',
        'status',
        'length_cm', 'width_cm', 'height_cm', 'weight_kg', 'area_m2',
        'is_featured', 'is_active', 'is_new', 'sort_order', 'views_count',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image',
    ];

    public array $translatable = [
        'name', 'slug', 'description', 'short_description',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'price_usd'       => 'decimal:2',
        'price_eur'       => 'decimal:2',
        'length_cm'       => 'decimal:2',
        'width_cm'        => 'decimal:2',
        'height_cm'       => 'decimal:2',
        'weight_kg'       => 'decimal:2',
        'area_m2'         => 'decimal:4',
        'price_on_request'=> 'boolean',
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
        'is_new'          => 'boolean',
        'views_count'     => 'integer',
    ];

    // ── Media ──────────────────────────────────────────
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')->singleFile();
        $this->addMediaCollection('thumbnail')->singleFile();
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('videos');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)->height(400)->sharpen(10)
            ->performOnCollections('main_image', 'gallery');

        $this->addMediaConversion('medium')
            ->width(800)->height(600)
            ->performOnCollections('main_image', 'gallery');

        $this->addMediaConversion('large')
            ->width(1400)->height(1000)
            ->performOnCollections('main_image', 'gallery');
    }

    // ── Relations ──────────────────────────────────────
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category')
            ->withPivot('is_primary');
    }

    public function primaryCategory()
    {
        return $this->categories()->wherePivot('is_primary', true)->first();
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class)
            ->with('attribute')
            ->orderBy('sort_order');
    }

    public function filterableAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class)
            ->whereHas('attribute', fn ($q) => $q->where('is_filterable', true))
            ->with('attribute')
            ->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeAvailable($q)
    {
        return $q->where('status', 'available');
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true);
    }

    public function scopeNew($q)
    {
        return $q->where('is_new', true);
    }

    public function scopeSold($q)
    {
        return $q->where('status', 'sold');
    }

    public function scopeReserved($q)
    {
        return $q->where('status', 'reserved');
    }

    public function scopeLatest($q)
    {
        return $q->orderBy('created_at', 'desc');
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function scopeInCategory($q, int $categoryId)
    {
        return $q->whereHas('categories', fn($c) => $c->where('categories.id', $categoryId));
    }

    public function scopePriceRange($q, ?float $min, ?float $max)
    {
        if ($min !== null) $q->where('price', '>=', $min);
        if ($max !== null) $q->where('price', '<=', $max);
        return $q;
    }

    public function scopeSearch($q, string $term)
    {
        return $q->where(function ($query) use ($term) {
            $query->whereRaw("JSON_SEARCH(LOWER(name), 'one', ?) IS NOT NULL", ["%{$term}%"])
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('mine_code', 'like', "%{$term}%");
        });
    }

    // ── Accessors ──────────────────────────────────────
    public function getMainImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('main_image', 'large')
            ?: asset('images/default-product.jpg');
    }

    public function getThumbUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('main_image', 'thumb')
            ?: asset('images/default-product.jpg');
    }

    public function getMediumImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('main_image', 'medium')
            ?: asset('images/default-product.jpg');
    }

    public function getGalleryUrlsAttribute(): array
    {
        return $this->getMedia('gallery')
            ->map(fn($m) => [
                'original' => $m->getUrl(),
                'medium'   => $m->getUrl('medium'),
                'thumb'    => $m->getUrl('thumb'),
            ])
            ->toArray();
    }

    public function getDisplayPriceAttribute(): ?string
    {
        if ($this->price_on_request) return null;
        if ($this->price) return number_format($this->price) . ' تومان';
        return null;
    }

    public function getDisplayPriceUsdAttribute(): ?string
    {
        if ($this->price_on_request || !$this->price_usd) return null;
        return '$' . number_format($this->price_usd, 2);
    }

    public function getDimensionsAttribute(): string
    {
        $parts = array_filter([
            $this->length_cm ? "{$this->length_cm}" : null,
            $this->width_cm  ? "{$this->width_cm}"  : null,
            $this->height_cm ? "{$this->height_cm}" : null,
        ]);
        return implode(' × ', $parts) . (count($parts) ? ' cm' : '');
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'available'   => 'موجود',
            'unavailable' => 'ناموجود',
            'reserved'    => 'رزرو شده',
            'sold'        => 'فروخته شده',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available'   => 'green',
            'unavailable' => 'gray',
            'reserved'    => 'yellow',
            'sold'        => 'red',
            default       => 'gray',
        };
    }

    // ── Helpers ────────────────────────────────────────
    public function isAvailable(): bool  { return $this->status === 'available'; }
    public function isSold(): bool       { return $this->status === 'sold'; }
    public function isReserved(): bool   { return $this->status === 'reserved'; }

    public function markAsReserved(): void
    {
        $this->update(['status' => 'reserved']);
    }

    public function markAsSold(): void
    {
        $this->update(['status' => 'sold']);
    }

    public function markAsAvailable(): void
    {
        $this->update(['status' => 'available']);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
