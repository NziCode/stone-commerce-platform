<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Event;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;

/**
 * Everything the "About us" page template shows besides the page's own text.
 *
 * Nothing here is hard-coded business content: numbers, stone photos, the exhibition
 * report, the owner's profile and the office addresses are all read from the CMS
 * (Settings, products, events, and a CMS page using the "profile" template), so the
 * page stays editable from the admin panel and hides any block that has no data yet.
 */
class AboutPage
{
    public static function build(Page $page, string $locale): array
    {
        $products = Product::query()->active()->available()
            ->with(['media', 'categories'])
            ->ordered()
            ->limit(6)
            ->get();

        $event = Event::published()->finished()->with('media')->get()
            ->first(fn (Event $e) => $e->photo_count >= 3);

        $founder = Page::active()->where('template', 'profile')->with('media')->orderBy('id')->first();

        $offices = static::offices((string) Setting::get('site_address', ''));
        $years = (int) Setting::get('about_years', 0);

        return [
            'page'     => $page,
            'locale'   => $locale,
            'hero'     => static::heroUrl($page, $event, $products->first()),
            'pillars'  => static::pillars(),
            'stats'    => static::stats($years, Product::active()->count(), $offices),
            'years'    => $years,
            'products' => $products,
            'event'    => $event,
            'eventPhotos' => $event ? static::sample($event->getMedia('gallery'), 5) : collect(),
            'founder'  => $founder,
            'offices'  => $offices,
            'story'    => static::storyImages($page, $products),
            'contact'  => [
                'phone' => display_phone(Setting::get('site_phone')),
                'email' => Setting::get('site_email'),
                'hours' => Setting::get('site_working_hours'),
                'map'   => static::mapUrl(),
            ],
        ];
    }

    /** "Title: description" settings → ['Title', 'description'] (title is null when there is no colon). */
    public static function splitFeature(?string $text): array
    {
        $text = trim(strip_tags((string) $text));

        if ($text === '') {
            return [null, ''];
        }

        // ASCII colon or the full-width one used in Chinese
        if (preg_match('/^(.{3,90}?)\s*[:：]\s*(.+)$/us', $text, $m)) {
            return [trim($m[1]), trim($m[2])];
        }

        return [null, $text];
    }

    /** The three "why us" pillars, taken from the About settings. */
    private static function pillars(): array
    {
        $pillars = [];

        foreach ([1, 2, 3] as $i) {
            [$title, $text] = static::splitFeature(Setting::get("about_feature_{$i}"));

            if ($text !== '') {
                $pillars[] = ['title' => $title, 'text' => $text];
            }
        }

        return $pillars;
    }

    /** One entry per line of the address setting: "Label: address". */
    private static function offices(string $raw): array
    {
        $offices = [];

        foreach (preg_split('/\R/u', $raw) as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            [$label, $text] = static::splitFeature($line);
            $offices[] = ['label' => $label, 'text' => $label ? $text : $line];
        }

        return $offices;
    }

    private static function stats(int $years, int $productTotal, array $offices): array
    {
        $families = Category::active()->roots()->count();

        return array_values(array_filter([
            $years > 0     ? ['value' => $years,           'suffix' => '+', 'label' => __('messages.ab_years_label')]    : null,
            $productTotal > 0 ? ['value' => $productTotal, 'suffix' => '', 'label' => __('messages.ab_stat_products')] : null,
            $families > 0  ? ['value' => $families,        'suffix' => '',  'label' => __('messages.ab_stat_families')] : null,
            count($offices) > 0 ? ['value' => count($offices), 'suffix' => '', 'label' => __('messages.ab_stat_offices')] : null,
        ]));
    }

    private static function heroUrl(Page $page, ?Event $event, ?Product $product): ?string
    {
        return $page->coverUrlFor('hero')
            ?: ($event?->getFirstMediaUrl('cover') ?: null)
            ?: ($product?->getFirstMediaUrl('main_image', 'large') ?: null);
    }

    /** Photos for the story collage: the page's own gallery, else the stone photos. */
    private static function storyImages(Page $page, $products): array
    {
        $images = $page->getMedia('gallery')->take(3)->map(fn ($m) => Page::mediaUrl($m, 'card'))->all();

        if (count($images) < 2) {
            $images = $products->take(3)->map(fn (Product $p) => $p->medium_image_url)->all();
        }

        return $images;
    }

    /** An evenly spaced sample, so a photo report shows its whole story (venue, products, people). */
    private static function sample($media, int $n)
    {
        $media = $media->values();
        $count = $media->count();

        if ($count <= $n) {
            return $media;
        }

        return collect(range(0, $n - 1))->map(fn ($i) => $media->get((int) floor($i * $count / $n)));
    }

    private static function mapUrl(): ?string
    {
        $lat = trim((string) Setting::get('site_map_lat', ''));
        $lng = trim((string) Setting::get('site_map_lng', ''));

        return $lat !== '' && $lng !== '' ? "https://www.google.com/maps?q={$lat},{$lng}" : null;
    }
}
