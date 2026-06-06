<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Traits\HasSeo;

class EventController extends Controller
{
    use HasSeo;

    public function index()
    {
        $upcomingEvents = Event::upcoming()->with('media')->get();
        $ongoingEvents  = Event::ongoing()->with('media')->get();
        $finishedEvents = Event::finished()->with('media')->paginate(6);

        return view('front.events.index', compact('upcomingEvents', 'ongoingEvents', 'finishedEvents'));
    }

    public function show(string $slug)
    {
        $locale = app()->getLocale();
        $event  = Event::whereJsonContains("slug->{$locale}", $slug)
            ->with('media')
            ->firstOrFail();

        $event->incrementViews();

        // SEO برای event
        $this->setSeo(
            title:       $event->getTranslation('meta_title', $locale) ?? $event->getTranslation('title', $locale),
            description: $event->getTranslation('meta_description', $locale) ?? '',
            image:       $event->cover_url,
        );

        return view('front.events.show', compact('event'));
    }
}
