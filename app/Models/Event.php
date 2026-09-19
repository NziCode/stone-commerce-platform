<?php

namespace App\Models;

use App\Support\Jalali;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'location', 'organizer_name', 'date_label',
        'city', 'country',
        'meta_title', 'meta_description', 'og_image',
        'starts_at', 'ends_at', 'status', 'is_published', 'auto_status',
        'website_url', 'booth_number', 'hall_number', 'views_count',
    ];

    public array $translatable = [
        'title', 'slug', 'description', 'location', 'organizer_name', 'date_label',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'views_count'  => 'integer',
        'is_published' => 'boolean',
        'auto_status'  => 'boolean',
    ];

    public const STATUSES = ['upcoming', 'ongoing', 'finished', 'cancelled'];

    protected static function booted(): void
    {
        // Public URLs are built from the slug of the visitor's language, so an
        // empty slug would break every link to the event. Fill any missing one
        // from that language's title (or the English title as a fallback).
        static::saving(function (Event $event) {
            $slugs  = $event->getTranslations('slug');
            $titles = $event->getTranslations('title');
            $base   = Str::slug($titles['en'] ?? '');

            foreach ($titles as $locale => $title) {
                if (! blank($slugs[$locale] ?? null) || blank($title)) {
                    continue;
                }

                $slug = Str::slug($title) ?: ($base !== '' ? "{$base}-{$locale}" : 'exhibition-' . strtolower(Str::random(6)));
                $event->setTranslation('slug', $locale, $slug);
            }
        });
    }

    // ── Media Collections ──────────────────────────────
    public function registerMediaCollections(): void
    {
        // Main cover image — shown in lists and as the detail-page hero
        $this->addMediaCollection('cover')->singleFile();

        // Photo gallery from the exhibition (booth, products on display, visitors, etc.).
        // Each photo can carry a per-language caption in its custom property
        // `caption` (["fa" => "...", "en" => "..."]) — see galleryCaption().
        $this->addMediaCollection('gallery');

        // Video(s) recorded at the exhibition (booth walkthrough, interviews, highlight reel)
        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/webm', 'video/quicktime']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(600)->height(400)
            ->nonOptimized()
            ->performOnCollections('cover', 'gallery');

        $this->addMediaConversion('medium')
            ->width(1200)->height(800)
            ->nonOptimized()
            ->performOnCollections('cover', 'gallery');

        // Poster/thumbnail frame for video previews
        $this->addMediaConversion('poster')
            ->width(800)->height(450)
            ->nonOptimized()
            ->performOnCollections('videos');
    }

    // ── Relations ──────────────────────────────────────
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Scopes ─────────────────────────────────────────
    /** Visible on the public site. */
    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    public function scopeUpcoming($q)
    {
        return $q->where('status', 'upcoming')
            ->orderByRaw('starts_at IS NULL')
            ->orderBy('starts_at');
    }

    public function scopeOngoing($q)
    {
        return $q->where('status', 'ongoing');
    }

    public function scopeFinished($q)
    {
        return $q->where('status', 'finished')
            ->orderBy('ends_at', 'desc')
            ->orderBy('id', 'desc');
    }

    public function scopeActive($q)
    {
        return $q->whereIn('status', ['upcoming', 'ongoing']);
    }

    /**
     * The "ongoing" section of the public list: what is running right now,
     * followed by what is coming next (undated events last).
     */
    public function scopeCurrent($q)
    {
        return $q->whereIn('status', ['ongoing', 'upcoming'])
            ->orderByRaw("CASE status WHEN 'ongoing' THEN 0 ELSE 1 END")
            ->orderByRaw('starts_at IS NULL')
            ->orderBy('starts_at');
    }

    // ── Status ─────────────────────────────────────────
    /**
     * The status this event should have right now according to its dates,
     * or null when the dates don't allow deciding (no start date, cancelled).
     */
    public function computeStatus(?CarbonInterface $now = null): ?string
    {
        if ($this->status === 'cancelled' || ! $this->starts_at) {
            return null;
        }

        $now ??= now();
        $start = $this->starts_at->copy()->startOfDay();
        $end   = ($this->ends_at ?? $this->starts_at)->copy()->endOfDay();

        return match (true) {
            $now->greaterThan($end)            => 'finished',
            $now->greaterThanOrEqualTo($start) => 'ongoing',
            default                            => 'upcoming',
        };
    }

    // ── Accessors ──────────────────────────────────────
    public function getCoverUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover', 'medium')
            ?: $this->getFirstMediaUrl('gallery', 'medium')
            ?: static::placeholderUrl();
    }

    public function getThumbUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover', 'thumb')
            ?: $this->getFirstMediaUrl('gallery', 'thumb')
            ?: static::placeholderUrl();
    }

    public function getHasImageAttribute(): bool
    {
        return $this->hasMedia('cover') || $this->hasMedia('gallery');
    }

    public function getPhotoCountAttribute(): int
    {
        return $this->getMedia('gallery')->count();
    }

    /** "35 photos" — with Persian digits for Persian readers. */
    public function getPhotoCountLabelAttribute(): string
    {
        $count = $this->photo_count;

        return __('messages.exh_photo_count', [
            'count' => app()->getLocale() === 'fa' ? Jalali::toPersianDigits($count) : $count,
        ]);
    }

    public static function placeholderUrl(): string
    {
        return asset('assets/images/event-placeholder.svg');
    }

    public function getStatusLabelAttribute(): string
    {
        return in_array($this->status, self::STATUSES, true)
            ? __('messages.exh_status_' . $this->status)
            : (string) $this->status;
    }

    /**
     * Human readable dates in the visitor's language: the admin-written
     * `date_label` wins (used for "Mehr 1405 — dates to be announced"),
     * otherwise the real dates — Jalali for Persian, localized Gregorian
     * for everything else.
     */
    public function dateText(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        $label = trim((string) $this->getTranslation('date_label', $locale, false));
        if ($label !== '') {
            return $label;
        }

        if (! $this->starts_at) {
            return __('messages.exh_dates_tbd');
        }

        if ($locale === 'fa') {
            return Jalali::formatRange($this->starts_at, $this->ends_at);
        }

        return static::formatGregorianRange($this->starts_at, $this->ends_at, $locale);
    }

    public function getDateTextAttribute(): string
    {
        return $this->dateText();
    }

    /** Where it takes place, in the visitor's language (the venue text already names the city). */
    public function venueText(?string $locale = null): string
    {
        $venue = trim((string) $this->getTranslation('location', $locale ?? app()->getLocale()));

        return $venue !== '' ? $venue : (string) $this->city;
    }

    /** CSS modifier for the status badge (theme: is-live / is-soon / is-past). */
    public function badgeClass(): string
    {
        return match ($this->status) {
            'ongoing'   => 'is-live',
            'upcoming'  => 'is-soon',
            'cancelled' => 'is-cancelled',
            default     => 'is-past',
        };
    }

    /** Short "year" tag, e.g. 1404 for Persian readers and 2025 for everyone else. */
    public function yearText(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();
        $date = $this->ends_at ?? $this->starts_at;

        if (! $date) {
            return null;
        }

        if ($locale === 'fa') {
            return Jalali::toPersianDigits(Jalali::fromGregorian($date->year, $date->month, $date->day)[0]);
        }

        return (string) $date->year;
    }

    public function getDurationAttribute(): string
    {
        return $this->dateText();
    }

    public function getIsUpcomingAttribute(): bool { return $this->status === 'upcoming'; }
    public function getIsOngoingAttribute(): bool  { return $this->status === 'ongoing'; }
    public function getIsFinishedAttribute(): bool { return $this->status === 'finished'; }

    public function isOngoing(): bool { return $this->status === 'ongoing'; }

    /** Whether this event has any video uploaded */
    public function getHasVideosAttribute(): bool
    {
        return $this->getMedia('videos')->isNotEmpty();
    }

    /**
     * Description ready for output. Plain-text descriptions (no tags) keep
     * their line breaks; rich-text ones are output as authored in the admin.
     */
    public function renderedDescription(?string $locale = null): string
    {
        $text = (string) $this->getTranslation('description', $locale ?? app()->getLocale());

        if ($text === strip_tags($text)) {
            return nl2br(e($text));
        }

        return $text;
    }

    /** Plain-text excerpt of the description for cards and meta tags. */
    public function excerpt(int $limit = 160, ?string $locale = null): string
    {
        $text = (string) $this->getTranslation('description', $locale ?? app()->getLocale());
        // keep a space where a paragraph / list item ended, or the sentences run together
        $text = preg_replace('/<\/(p|li|h[1-6]|div|ul|ol)>|<br\s*\/?>/i', '$0 ', $text);
        $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($text))));

        return Str::limit($text, $limit, '…', preserveWords: true);
    }

    /** Caption (or alt text) of a gallery photo in the given locale, with an English fallback. */
    public function galleryCaption(Media $media, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $captions = (array) $media->getCustomProperty('caption', []);

        return trim((string) ($captions[$locale] ?? $captions['en'] ?? $captions['fa'] ?? ''));
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // ── Helpers ────────────────────────────────────────
    private static function formatGregorianRange(CarbonInterface $start, ?CarbonInterface $end, string $locale): string
    {
        $start = $start->copy()->locale($locale);
        $end   = $end?->copy()->locale($locale);

        // Chinese reads best as 2025年10月7日
        if ($locale === 'zh') {
            if (! $end || $end->isSameDay($start)) {
                return $start->translatedFormat('Y年n月j日');
            }
            if ($start->isSameMonth($end)) {
                return $start->translatedFormat('Y年n月j日') . '–' . $end->translatedFormat('j日');
            }

            return $start->translatedFormat('Y年n月j日') . ' – ' . ($start->year === $end->year
                ? $end->translatedFormat('n月j日')
                : $end->translatedFormat('Y年n月j日'));
        }

        if (! $end || $end->isSameDay($start)) {
            return $start->translatedFormat('j F Y');
        }

        if ($start->isSameMonth($end)) {
            return $start->translatedFormat('j') . '–' . $end->translatedFormat('j F Y');
        }

        return $start->year === $end->year
            ? $start->translatedFormat('j F') . ' – ' . $end->translatedFormat('j F Y')
            : $start->translatedFormat('j F Y') . ' – ' . $end->translatedFormat('j F Y');
    }
}
