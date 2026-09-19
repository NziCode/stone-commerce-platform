@extends('front.layouts.app')

@section('title', __('messages.events') . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/exhibitions.css') }}?v={{ @filemtime(public_path('assets/css/exhibitions.css')) ?: 1 }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/exhibitions.js') }}?v={{ @filemtime(public_path('assets/js/exhibitions.js')) ?: 1 }}" defer></script>
@endpush

@section('content')
    @php
        $num = fn (int $n) => $locale === 'fa' ? \App\Support\Jalali::toPersianDigits($n) : $n;
        $filters = [
            'all'     => ['label' => __('messages.exh_filter_all'),     'count' => $num($currentEvents->count() + $heldEvents->count())],
            'ongoing' => ['label' => __('messages.exh_filter_ongoing'), 'count' => $num($currentEvents->count())],
            'held'    => ['label' => __('messages.exh_filter_held'),    'count' => $num($heldEvents->count())],
        ];
        $featured = $currentEvents->first();
    @endphp

    @include('front.components.breadcrumb', [
        'subtitle' => \App\Models\Setting::get('site_name'),
        'title'    => __('messages.events'),
        'desc'     => __('messages.exh_intro'),
    ])

    <div class="mt-section">
        <div class="mt-container" data-exh-filter="{{ $filter }}">

            {{-- ── Filter ── --}}
            <ul class="exh-filters" role="group" aria-label="{{ __('messages.events') }}">
                @foreach($filters as $key => $item)
                    <li>
                        <a href="{{ $key === 'all' ? route('events.index') : route('events.index', ['filter' => $key]) }}"
                           class="exh-filter {{ $filter === $key ? 'is-active' : '' }}"
                           data-exh-pill="{{ $key }}"
                           aria-pressed="{{ $filter === $key ? 'true' : 'false' }}">
                            {{ $item['label'] }}
                            <span class="count">{{ $item['count'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- ═══ Ongoing & upcoming ═══ --}}
            <section class="exh-section" data-exh-section="ongoing">
                <div class="exh-section-head">
                    <span class="dot"></span>
                    <h2>{{ __('messages.exh_filter_ongoing') }}</h2>
                </div>

                @if($featured)
                    @php
                        $fUrl   = route('events.show', $featured->getTranslation('slug', $locale));
                        $fVenue = $featured->venueText($locale);
                    @endphp
                    <article class="exh-featured">
                        <a class="exh-featured-media" href="{{ $fUrl }}" tabindex="-1" aria-hidden="true">
                            <img src="{{ $featured->cover_url }}" alt="" loading="lazy">
                        </a>
                        <div class="exh-featured-body">
                            <span class="exh-badge {{ $featured->badgeClass() }}" style="width:fit-content">{{ $featured->status_label }}</span>
                            <h3><a href="{{ $fUrl }}">{{ $featured->getTranslation('title', $locale) }}</a></h3>
                            <div class="exh-meta" style="font-size:.86rem">
                                <span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    {{ $featured->date_text }}
                                </span>
                                @if($fVenue)
                                    <span>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $fVenue }}
                                    </span>
                                @endif
                            </div>
                            @if($excerpt = $featured->excerpt(260, $locale))
                                <p>{{ $excerpt }}</p>
                            @endif
                            <div style="display:flex;flex-wrap:wrap;gap:.7rem">
                                <a href="{{ $fUrl }}" class="mt-btn mt-btn-primary">{{ __('messages.view_details') }}</a>
                                @if($featured->website_url)
                                    <a href="{{ $featured->website_url }}" target="_blank" rel="noopener noreferrer" class="mt-btn mt-btn-outline">{{ __('messages.event_website') }}</a>
                                @endif
                            </div>
                        </div>
                    </article>

                    @if($currentEvents->count() > 1)
                        <div class="row g-4">
                            @foreach($currentEvents->skip(1) as $event)
                                <div class="col-md-6 col-lg-4">
                                    @include('front.components.event-card', ['event' => $event, 'locale' => $locale])
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="exh-empty">{{ __('messages.exh_empty_ongoing') }}</p>
                @endif
            </section>

            {{-- ═══ Held ═══ --}}
            <section class="exh-section" data-exh-section="held">
                <div class="exh-section-head">
                    <span class="dot is-held"></span>
                    <h2>{{ __('messages.exh_filter_held') }}</h2>
                </div>

                @if($heldEvents->count())
                    <div class="row g-4">
                        @foreach($heldEvents as $event)
                            <div class="col-md-6 col-lg-4">
                                @include('front.components.event-card', ['event' => $event, 'locale' => $locale])
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="exh-empty">{{ __('messages.exh_empty_held') }}</p>
                @endif
            </section>

        </div>
    </div>
@endsection
