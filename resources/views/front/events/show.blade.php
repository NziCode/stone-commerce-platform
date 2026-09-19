@extends('front.layouts.app')

@section('title', $event->getTranslation('title', $locale) . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/exhibitions.css') }}?v={{ @filemtime(public_path('assets/css/exhibitions.css')) ?: 1 }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/exhibitions.js') }}?v={{ @filemtime(public_path('assets/js/exhibitions.js')) ?: 1 }}" defer></script>
@endpush

@section('content')
    @php
        $title   = $event->getTranslation('title', $locale);
        $gallery = $event->getMedia('gallery');
        $videos  = $event->getMedia('videos');
        $venue   = $event->venueText($locale);
        $organizer = $event->getTranslation('organizer_name', $locale);
        $rtl     = in_array($locale, ['fa', 'ar']);
        $icon    = fn (string $paths) => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14">' . $paths . '</svg>';
    @endphp

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.events'),
        'title'    => $title,
        'crumbs'   => [
            ['label' => __('messages.events'), 'url' => route('events.index')],
            ['label' => Str::limit($title, 40)],
        ],
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row">

                {{-- ── Main content ── --}}
                <div class="col-lg-8 order-lg-1 order-2">

                    <div class="exh-hero">
                        <img src="{{ $event->cover_url }}" alt="{{ $title }}">
                        <span class="exh-badge {{ $event->badgeClass() }}">{{ $event->status_label }}</span>
                    </div>

                    <div class="exh-meta" style="font-size:.86rem;margin-bottom:1rem">
                        <span>{!! $icon('<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>') !!} {{ $event->date_text }}</span>
                        @if($venue)
                            <span>{!! $icon('<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>') !!} {{ $venue }}</span>
                        @endif
                    </div>

                    <h2 class="exh-title">{{ $title }}</h2>

                    @if(trim(strip_tags((string) $event->getTranslation('description', $locale))) !== '')
                        <div class="exh-prose">{!! $event->renderedDescription($locale) !!}</div>
                    @endif

                    {{-- Photo gallery --}}
                    @if($gallery->count())
                        <div class="exh-gallery-head">
                            <h2>{{ __('messages.exh_gallery') }}</h2>
                            <span>{{ $event->photo_count_label }}</span>
                        </div>
                        <div class="exh-gallery"
                             data-exh-labels
                             data-exh-close="{{ __('messages.exh_close') }}"
                             data-exh-prev="{{ __('messages.exh_prev') }}"
                             data-exh-next="{{ __('messages.exh_next') }}">
                            @foreach($gallery as $media)
                                @php $caption = $event->galleryCaption($media, $locale); @endphp
                                <a href="{{ $media->getUrl() }}"
                                   data-exh-lightbox
                                   data-caption="{{ $caption }}"
                                   aria-label="{{ $caption !== '' ? $caption : $title . ' — ' . $loop->iteration }}">
                                    <img src="{{ $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl() }}"
                                         alt="{{ $caption !== '' ? $caption : $title }}"
                                         loading="lazy" decoding="async">
                                </a>
                            @endforeach
                        </div>
                    @elseif($event->status === 'finished')
                        <div class="exh-soon">
                            {!! $icon('<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/>') !!}
                            <span>{{ __('messages.exh_gallery_soon') }}</span>
                        </div>
                    @endif

                    {{-- Videos --}}
                    @if($videos->count())
                        <div class="exh-gallery-head">
                            <h2>{{ __('messages.exh_videos') }}</h2>
                        </div>
                        <div class="exh-videos">
                            @foreach($videos as $media)
                                <video controls preload="metadata"
                                       @if($media->hasGeneratedConversion('poster')) poster="{{ $media->getUrl('poster') }}" @endif>
                                    <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                                </video>
                            @endforeach
                        </div>
                    @endif

                    <div style="margin-top:2.4rem">
                        <a class="mt-btn mt-btn-outline" href="{{ route('events.index') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" style="{{ $rtl ? '' : 'transform:scaleX(-1)' }}"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            {{ __('messages.exh_back') }}
                        </a>
                    </div>
                </div>

                {{-- ── Sidebar ── --}}
                <div class="col-lg-4 order-lg-2 order-1 pt-10 pt-lg-0">
                    <div style="display:grid;gap:1.6rem">

                        <div class="exh-facts">
                            <h3>{{ __('messages.exh_details') }}</h3>
                            <dl>
                                <div class="row-item">
                                    <dt>{{ __('messages.exh_dates') }}</dt>
                                    <dd>{{ $event->date_text }}</dd>
                                </div>
                                @if($venue)
                                    <div class="row-item">
                                        <dt>{{ __('messages.exh_venue') }}</dt>
                                        <dd>{{ $venue }}</dd>
                                    </div>
                                @endif
                                @if($organizer)
                                    <div class="row-item">
                                        <dt>{{ __('messages.exh_organizer') }}</dt>
                                        <dd>{{ $organizer }}</dd>
                                    </div>
                                @endif
                                @if($event->hall_number)
                                    <div class="row-item">
                                        <dt>{{ __('admin.hall_number') }}</dt>
                                        <dd>{{ $event->hall_number }}</dd>
                                    </div>
                                @endif
                                @if($event->booth_number)
                                    <div class="row-item">
                                        <dt>{{ __('admin.booth_number') }}</dt>
                                        <dd>{{ $event->booth_number }}</dd>
                                    </div>
                                @endif
                            </dl>
                            @if($event->website_url)
                                <a href="{{ $event->website_url }}" target="_blank" rel="noopener noreferrer" class="mt-btn mt-btn-primary exh-site">
                                    {{ __('messages.event_website') }}
                                </a>
                            @endif
                        </div>

                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">{{ __('messages.any_questions') }}</h3>
                            <a href="{{ route('contact') }}" class="mt-btn mt-btn-ink" style="width:100%;justify-content:center">
                                {{ __('messages.contact') }}
                            </a>
                        </div>

                        @if($otherEvents->count())
                            <div class="sidebar-widget">
                                <h3 class="sidebar-title">{{ __('messages.exh_other') }}</h3>
                                <div style="display:grid;gap:1rem">
                                    @foreach($otherEvents as $other)
                                        <a class="exh-other" href="{{ route('events.show', $other->getTranslation('slug', $locale)) }}">
                                            <span class="thumb"><img src="{{ $other->thumb_url }}" alt="" loading="lazy"></span>
                                            <span>
                                                <strong>{{ Str::limit($other->getTranslation('title', $locale), 60) }}</strong>
                                                <small>{{ $other->date_text }}</small>
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
