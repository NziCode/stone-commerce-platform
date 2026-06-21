@php
    $badge = isset($finished) && $finished
        ? 'is-past'
        : ($event->isOngoing() ? 'is-live' : 'is-soon');
@endphp
<div class="col-md-6 col-lg-4 mb-8">
    <div class="mt-post">
        <a class="mt-post-img" href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
            <img src="{{ $event->cover_url }}" alt="{{ $event->getTranslation('title', app()->getLocale()) }}" loading="lazy">
        </a>
        <div class="mt-post-body">
            <div class="mt-post-meta">
                @if($event->starts_at)
                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>{{ $event->starts_at->format('d M Y') }}</span>
                @endif
                @if($event->city)
                    <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $event->city }}</span>
                @endif
            </div>
            <h3 class="mt-post-title">
                <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                    {{ $event->getTranslation('title', app()->getLocale()) }}
                </a>
            </h3>
            @if($event->booth_number)
                <p class="mt-post-excerpt">
                    {{ __('messages.booth') ?? 'Booth' }}: {{ $event->booth_number }}
                    @if($event->hall_number) — {{ __('messages.hall') ?? 'Hall' }}: {{ $event->hall_number }} @endif
                </p>
            @endif
            <div style="display:flex;align-items:center;justify-content:space-between;gap:.6rem">
                <a class="mt-post-more" href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                    {{ __('messages.more_information') }}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
                <span class="mt-event-badge {{ $badge }}">{{ $event->status_label }}</span>
            </div>
        </div>
    </div>
</div>
