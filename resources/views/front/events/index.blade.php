@extends('front.layouts.app')

@section('title', __('messages.events') . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <style>
        /* ── Status badges ──────────────────────────── */
        .event-status-badge {
            display: inline-block; padding: 4px 14px;
            font-size: 12px; font-weight: 700; letter-spacing: 0.3px;
        }
        .status-upcoming { background: #e8f0fe; color: #1a56db; }
        .status-ongoing  { background: #e8f7ee; color: #2d8a4e; }
        .status-finished { background: #f0f0f0; color: #888; }

        /* ── Section heading ────────────────────────── */
        .events-section-title {
            font-size: 28px; color: #00225a; font-weight: 700;
            margin-bottom: 30px; padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0; position: relative;
        }
        .events-section-title::after {
            content: ''; position: absolute; bottom: -2px; left: 0;
            width: 60px; height: 2px; background: #ff5e13;
        }
        [dir="rtl"] .events-section-title::after { left: auto; right: 0; }

        /* ── Event card ─────────────────────────────── */
        .event-card {
            border: 1px solid #eee; height: 100%;
            transition: box-shadow 0.3s ease;
            display: flex; flex-direction: column;
        }
        .event-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.1); }
        .event-card .event-img { position: relative; overflow: hidden; }
        .event-card .event-img img {
            width: 100%; height: 220px; object-fit: cover;
            transition: transform 0.4s ease; display: block;
        }
        .event-card:hover .event-img img { transform: scale(1.05); }
        .event-card .event-img .event-status-badge {
            position: absolute; top: 12px; inset-inline-start: 12px; z-index: 2;
        }
        .event-card .event-body { padding: 18px; flex: 1; display: flex; flex-direction: column; }
        .event-card .event-date {
            font-size: 13px; color: #ff5e13; font-weight: 600; margin-bottom: 8px;
            display: flex; align-items: center; gap: 6px;
        }
        .event-card .event-title { font-size: 18px; font-weight: 700; margin-bottom: 10px; line-height: 1.4; }
        .event-card .event-title a { color: #00225a; }
        .event-card .event-title a:hover { color: #ff5e13; }
        .event-card .event-location {
            font-size: 13px; color: #888; margin-top: auto; padding-top: 10px;
            display: flex; align-items: center; gap: 6px;
        }

        /* ── Featured (first upcoming) card ─────────── */
        .event-featured {
            display: flex; border: 1px solid #eee; margin-bottom: 40px;
            overflow: hidden;
        }
        .event-featured .featured-img { width: 45%; flex-shrink: 0; }
        .event-featured .featured-img img { width: 100%; height: 100%; object-fit: cover; display: block; min-height: 320px; }
        .event-featured .featured-body { padding: 36px; display: flex; flex-direction: column; justify-content: center; }
        .event-featured .featured-body .event-title { font-size: 28px; margin-bottom: 14px; }
        .event-featured .featured-body .event-desc { font-size: 14px; color: #666; line-height: 1.8; margin-bottom: 20px; }
        @media (max-width: 768px) {
            .event-featured { flex-direction: column; }
            .event-featured .featured-img { width: 100%; }
        }

        /* ── Empty state ─────────────────────────────── */
        .events-empty { text-align: center; padding: 40px 20px; color: #999; font-size: 14px; }
    </style>
@endpush

@section('content')

    {{-- Breadcrumb --}}
    <div class="breadcrumb-area breadcrumb-height"
         data-bg-image="{{ asset('assets/images/breadcrumb/bg/1.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <span class="breadcrumb-sub-title">{{ __('messages.events') }}</span>
                <h1 class="breadcrumb-title mb-1">{{ __('messages.events') }}</h1>
                <ul class="breadcrumb" style="background:none;padding:0;margin:10px 0 0;display:flex;align-items:center;justify-content:center;list-style:none;direction:ltr">
                    <li><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.75)">{{ __('messages.home') }}</a></li>
                    <li style="color:rgba(255,255,255,0.4);padding:0 8px">/</li>
                    <li style="color:#ff5e13;font-weight:600">{{ __('messages.events') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="events-area py-140">
        <div class="container">

            {{-- ═══ Upcoming Events ═══ --}}
            @if($upcomingEvents->count())
                <div class="upcoming-events mb-9">
                    <h2 class="events-section-title">{{ __('admin.upcoming') }}</h2>

                    {{-- Featured: first upcoming event --}}
                    @php $featured = $upcomingEvents->first(); @endphp
                    <div class="event-featured">
                        <div class="featured-img">
                            <img src="{{ $featured->cover_url }}"
                                 alt="{{ $featured->getTranslation('title', app()->getLocale()) }}">
                        </div>
                        <div class="featured-body">
                            <span class="event-status-badge status-upcoming mb-3" style="width:fit-content">
                                {{ $featured->status_label }}
                            </span>
                            <h3 class="event-title">
                                <a href="{{ route('events.show', $featured->getTranslation('slug', app()->getLocale())) }}">
                                    {{ $featured->getTranslation('title', app()->getLocale()) }}
                                </a>
                            </h3>
                            @if($featured->getTranslation('description', app()->getLocale()))
                                <p class="event-desc">
                                    {{ Str::limit($featured->getTranslation('description', app()->getLocale()), 180) }}
                                </p>
                            @endif
                            <div class="event-date mb-2">
                                <i class="fa fa-calendar"></i>
                                {{ $featured->starts_at?->format('d M Y') }}
                                @if($featured->ends_at) — {{ $featured->ends_at->format('d M Y') }} @endif
                            </div>
                            @if($featured->city || $featured->getTranslation('location', app()->getLocale()))
                                <div class="event-location mb-4" style="padding-top:0">
                                    <i class="fa fa-map-marker"></i>
                                    {{ $featured->getTranslation('location', app()->getLocale()) }}
                                    @if($featured->city), {{ $featured->city }}@endif
                                </div>
                            @endif
                            <a href="{{ route('events.show', $featured->getTranslation('slug', app()->getLocale())) }}"
                               class="btn btn-custom btn-primary btn-secondary-hover" style="width:fit-content">
                                {{ __('messages.view_details') }}
                            </a>
                        </div>
                    </div>

                    {{-- Remaining upcoming events --}}
                    @if($upcomingEvents->count() > 1)
                        <div class="row">
                            @foreach($upcomingEvents->skip(1) as $event)
                                <div class="col-md-4 col-sm-6 mb-6">
                                    <div class="event-card">
                                        <div class="event-img">
                                            <span class="event-status-badge status-upcoming">{{ $event->status_label }}</span>
                                            <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                                                <img src="{{ $event->cover_url }}"
                                                     alt="{{ $event->getTranslation('title', app()->getLocale()) }}">
                                            </a>
                                        </div>
                                        <div class="event-body">
                                            <span class="event-date">
                                                <i class="fa fa-calendar"></i>
                                                {{ $event->starts_at?->format('d M Y') }}
                                            </span>
                                            <h4 class="event-title">
                                                <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                                                    {{ $event->getTranslation('title', app()->getLocale()) }}
                                                </a>
                                            </h4>
                                            @if($event->city)
                                                <span class="event-location">
                                                    <i class="fa fa-map-marker"></i> {{ $event->city }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- ═══ Ongoing Events ═══ --}}
            @if($ongoingEvents->count())
                <div class="ongoing-events mb-9">
                    <h2 class="events-section-title">{{ __('admin.ongoing') }}</h2>
                    <div class="row">
                        @foreach($ongoingEvents as $event)
                            <div class="col-md-4 col-sm-6 mb-6">
                                <div class="event-card">
                                    <div class="event-img">
                                        <span class="event-status-badge status-ongoing">{{ $event->status_label }}</span>
                                        <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                                            <img src="{{ $event->cover_url }}"
                                                 alt="{{ $event->getTranslation('title', app()->getLocale()) }}">
                                        </a>
                                    </div>
                                    <div class="event-body">
                                        <span class="event-date">
                                            <i class="fa fa-calendar"></i>
                                            {{ $event->starts_at?->format('d M Y') }}
                                            @if($event->ends_at) — {{ $event->ends_at->format('d M Y') }} @endif
                                        </span>
                                        <h4 class="event-title">
                                            <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                                                {{ $event->getTranslation('title', app()->getLocale()) }}
                                            </a>
                                        </h4>
                                        @if($event->city)
                                            <span class="event-location">
                                                <i class="fa fa-map-marker"></i> {{ $event->city }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ═══ Finished Events ═══ --}}
            <div class="finished-events">
                <h2 class="events-section-title">{{ __('admin.finished') }}</h2>
                @if($finishedEvents->count())
                    <div class="row">
                        @foreach($finishedEvents as $event)
                            <div class="col-md-4 col-sm-6 mb-6">
                                <div class="event-card" style="opacity:0.85">
                                    <div class="event-img">
                                        <span class="event-status-badge status-finished">{{ $event->status_label }}</span>
                                        <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                                            <img src="{{ $event->cover_url }}"
                                                 alt="{{ $event->getTranslation('title', app()->getLocale()) }}"
                                                 style="filter:grayscale(30%)">
                                        </a>
                                    </div>
                                    <div class="event-body">
                                        <span class="event-date">
                                            <i class="fa fa-calendar"></i>
                                            {{ $event->ends_at?->format('d M Y') }}
                                        </span>
                                        <h4 class="event-title">
                                            <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                                                {{ $event->getTranslation('title', app()->getLocale()) }}
                                            </a>
                                        </h4>
                                        @if($event->city)
                                            <span class="event-location">
                                                <i class="fa fa-map-marker"></i> {{ $event->city }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pt-8">
                        {{ $finishedEvents->links() }}
                    </div>
                @else
                    <p class="events-empty">{{ __('messages.no_products') }}</p>
                @endif
            </div>

            @if(!$upcomingEvents->count() && !$ongoingEvents->count() && !$finishedEvents->count())
                <div class="events-empty">
                    <i class="ion-calendar" style="font-size:48px;color:#dee2e6;display:block;margin-bottom:16px"></i>
                    {{ __('messages.no_products') }}
                </div>
            @endif

        </div>
    </div>

@endsection
