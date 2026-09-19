{{-- One exhibition card. Vars: $event, optional $locale. The caller provides the grid column. --}}
@php
    $locale = $locale ?? app()->getLocale();
    $url    = route('events.show', $event->getTranslation('slug', $locale));
    $title  = $event->getTranslation('title', $locale);
    $venue  = $event->venueText($locale);
    $photos = $event->photo_count;
@endphp
<article class="exh-card">
    <a class="exh-card-media" href="{{ $url }}" tabindex="-1" aria-hidden="true">
        <img src="{{ $event->thumb_url }}" alt="" loading="lazy">
        <span class="exh-badge {{ $event->badgeClass() }}">{{ $event->status_label }}</span>
        @if($photos)
            <span class="exh-photos">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                {{ $event->photo_count_label }}
            </span>
        @endif
    </a>
    <div class="exh-card-body">
        <div class="exh-meta">
            <span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                {{ $event->date_text }}
            </span>
            @if($venue)
                <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ \Illuminate\Support\Str::limit($venue, 46) }}
                </span>
            @endif
        </div>
        <h3 class="exh-card-title"><a href="{{ $url }}">{{ $title }}</a></h3>
        @if($excerpt = $event->excerpt(140, $locale))
            <p class="exh-card-excerpt">{{ $excerpt }}</p>
        @endif
        <div class="exh-card-foot">
            <a class="mt-post-more" href="{{ $url }}">
                {{ $event->status === 'finished' && $photos ? __('messages.exh_view_gallery') : __('messages.view_details') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" style="{{ in_array($locale, ['fa','ar']) ? 'transform:scaleX(-1)' : '' }}"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>
    </div>
</article>
