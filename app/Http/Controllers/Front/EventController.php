<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Traits\HasSeo;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use HasSeo;

    /** Public filters of the exhibitions list. */
    private const FILTERS = ['all', 'held', 'ongoing'];

    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $filter = in_array($filter, self::FILTERS, true) ? $filter : 'all';

        // "Ongoing" = running now + coming next; "held" = already finished.
        $currentEvents = Event::published()->current()->with('media')->get();
        $heldEvents    = Event::published()->finished()->with('media')->limit(60)->get();

        $locale = app()->getLocale();
        $this->setSeo(
            title:       __('messages.events'),
            description: __('messages.exh_intro'),
            image:       $currentEvents->merge($heldEvents)->first(fn (Event $e) => $e->has_image)?->cover_url ?? '',
        );

        return view('front.events.index', compact('filter', 'currentEvents', 'heldEvents', 'locale'));
    }

    public function show(string $slug)
    {
        $locale = app()->getLocale();
        $event  = Event::published()
            ->whereJsonContains("slug->{$locale}", $slug)
            ->with('media')
            ->firstOrFail();

        $event->incrementViews();

        $title       = $event->getTranslation('meta_title', $locale) ?: $event->getTranslation('title', $locale);
        $description = $event->getTranslation('meta_description', $locale) ?: $event->excerpt(200);

        $this->setSeo(
            title:       $title,
            description: $description,
            image:       $event->cover_url,
            schemaData:  $this->schemaFor($event, $title, $description, $locale),
        );

        $otherEvents = Event::published()
            ->whereKeyNot($event->getKey())
            ->whereIn('status', ['upcoming', 'ongoing', 'finished'])
            ->orderByRaw("CASE status WHEN 'ongoing' THEN 0 WHEN 'upcoming' THEN 1 ELSE 2 END")
            ->orderBy('starts_at', 'desc')
            ->with('media')
            ->limit(3)
            ->get();

        return view('front.events.show', compact('event', 'otherEvents', 'locale'));
    }

    /** schema.org/Event, only when the exhibition has real dates. */
    private function schemaFor(Event $event, string $title, string $description, string $locale): array
    {
        if (! $event->starts_at) {
            return [];
        }

        $status = match ($event->status) {
            'cancelled' => 'https://schema.org/EventCancelled',
            default     => 'https://schema.org/EventScheduled',
        };

        $place = array_filter([
            '@type'   => 'Place',
            'name'    => $event->getTranslation('location', $locale) ?: null,
            'address' => array_filter([
                '@type'           => 'PostalAddress',
                'addressLocality' => $event->city,
                'addressCountry'  => $event->country,
            ]),
        ]);

        return array_filter([
            '@type'               => 'Event',
            'name'                => $title,
            'description'         => $description,
            'startDate'           => $event->starts_at->toDateString(),
            'endDate'             => ($event->ends_at ?? $event->starts_at)->toDateString(),
            'eventStatus'         => $status,
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'image'               => $event->cover_url,
            'url'                 => url()->current(),
            'location'            => $place,
            'organizer'           => $event->getTranslation('organizer_name', $locale)
                ? ['@type' => 'Organization', 'name' => $event->getTranslation('organizer_name', $locale)]
                : null,
        ]);
    }
}
