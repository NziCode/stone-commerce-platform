<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * The top-level grouping of the catalogue: export grade, saw-cut and top-cut stones.
 * A stone belongs to exactly one; visitors filter by it (?group=<key>).
 */
class MainCategory extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    public const EXPORT = 'export';

    protected $fillable = ['key', 'name', 'description', 'sort_order', 'is_active'];

    public array $translatable = ['name', 'description'];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('card')->width(700)->height(460)->nonOptimized()->performOnCollections('image');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    /** The picture of this group: the uploaded one, otherwise the built-in illustration for its key. */
    public function imageUrl(): ?string
    {
        if ($this->hasMedia('image')) {
            return $this->hasGeneratedConversion('card', 'image') ? $this->getFirstMediaUrl('image', 'card') : $this->getFirstMediaUrl('image');
        }

        $file = "assets/images/groups/{$this->key}.svg";

        return file_exists(public_path($file)) ? asset($file) : null;
    }

    /** Link that lists this group's stones on the public site. */
    public function url(): string
    {
        return route('products.index', ['group' => $this->key]);
    }

    /** id of the group new stones fall into when none is chosen (export). */
    public static function defaultId(): ?int
    {
        return static::where('key', self::EXPORT)->value('id');
    }

    private function hasGeneratedConversion(string $conversion, string $collection): bool
    {
        $media = $this->getFirstMedia($collection);

        return $media !== null && $media->hasGeneratedConversion($conversion);
    }
}
