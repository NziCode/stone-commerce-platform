<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\HasSeo;

class PageController extends Controller
{
    use HasSeo;

    public function show(string $slug)
    {
        $locale = app()->getLocale();
        $page   = Page::active()
            ->whereJsonContains("slug->{$locale}", $slug)
            ->with('media')
            ->firstOrFail();

        $page->incrementViews();

        // SEO برای صفحه
        $this->setSeo(
            title:       $page->getTranslation('meta_title', $locale) ?? $page->getTranslation('title', $locale),
            description: $page->getTranslation('meta_description', $locale) ?? $page->getTranslation('excerpt', $locale) ?? '',
            image:       $page->cover_url,
        );

        return view('front.pages.show', compact('page'));
    }
}
