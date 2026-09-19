<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\AboutPage;
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

        // getTranslation() returns '' (not null) for a missing locale, so fall back with ?: here.
        $this->setSeo(
            title:       (string) ($page->getTranslation('meta_title', $locale) ?: $page->getTranslation('title', $locale)),
            description: (string) ($page->getTranslation('meta_description', $locale) ?: $page->getTranslation('excerpt', $locale)),
            image:       $page->cover_url,
        );

        return match ($page->template) {
            'about'   => view('front.pages.about', AboutPage::build($page, $locale)),
            'profile' => view('front.pages.profile', compact('page', 'locale')),
            default   => view('front.pages.show', compact('page')),
        };
    }
}
