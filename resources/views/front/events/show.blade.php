@extends('front.layouts.app')

@section('title', $event->getTranslation('title', app()->getLocale()) . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
    <style>
        .event-status-badge {
            display: inline-block; padding: 5px 16px;
            font-size: 13px; font-weight: 700; margin-bottom: 16px;
        }
        .status-upcoming { background: #e8f0fe; color: #1a56db; }
        .status-ongoing  { background: #e8f7ee; color: #2d8a4e; }
        .status-finished { background: #f0f0f0; color: #888; }

        .event-cover { width: 100%; height: 420px; object-fit: cover; display: block; }

        .event-description { font-size: 15px; color: #444; line-height: 1.9; margin-top: 24px; }
        .event-description img { max-width: 100%; height: auto; margin: 16px 0; }

        /* ── Gallery ─────────────────────────────────── */
        .event-gallery { margin-top: 30px; }
        .event-gallery img {
            width: 100%; height: 160px; object-fit: cover; cursor: pointer;
            transition: opacity 0.2s;
        }
        .event-gallery img:hover { opacity: 0.85; }

        /* ── Sidebar info widget ─────────────────────── */
        .event-info-widget { background: #00225a; padding: 28px 24px; }
        .event-info-widget h3.title {
            color: #fff; font-size: 18px; margin-bottom: 18px;
            padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .event-info-widget ul { list-style: none; padding: 0; margin: 0; }
        .event-info-widget ul li {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; font-size: 13px; color: rgba(255,255,255,0.65);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .event-info-widget ul li:last-child { border-bottom: none; }
        .event-info-widget ul li span { color: #fff; font-weight: 600; text-align: end; }

        .event-info-widget .website-btn {
            display: block; text-align: center; margin-top: 20px;
            padding: 10px; background: #ff5e13; color: #fff;
            font-size: 14px; font-weight: 600; text-decoration: none;
        }
        .event-info-widget .website-btn:hover { background: #e04d00; color: #fff; }

        .sidebar-cta { background: #ff5e13; padding: 24px 20px; text-align: center; margin-top: 24px; }
        .sidebar-cta h4 { color: #fff; font-size: 15px; margin-bottom: 14px; }

        [dir="rtl"] .event-info-widget ul li span { text-align: start; }
    </style>
@endpush

@section('content')

    {{-- Breadcrumb --}}
    <div class="breadcrumb-area breadcrumb-height"
         data-bg-image="{{ asset('assets/images/breadcrumb/bg/1.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <span class="breadcrumb-sub-title">{{ __('messages.events') }}</span>
                <h1 class="breadcrumb-title mb-1">{{ $event->getTranslation('title', app()->getLocale()) }}</h1>
                <ul class="breadcrumb" style="background:none;padding:0;margin:10px 0 0;display:flex;align-items:center;justify-content:center;list-style:none;direction:ltr">
                    <li><a href="{{ route('home') }}" style="color:rgba(255,255,255,0.75)">{{ __('messages.home') }}</a></li>
                    <li style="color:rgba(255,255,255,0.4);padding:0 8px">/</li>
                    <li><a href="{{ route('events.index') }}" style="color:rgba(255,255,255,0.75)">{{ __('messages.events') }}</a></li>
                    <li style="color:rgba(255,255,255,0.4);padding:0 8px">/</li>
                    <li style="color:#ff5e13;font-weight:600">
                        {{ Str::limit($event->getTranslation('title', app()->getLocale()), 40) }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="event-detail-area py-140">
        <div class="container">
            <div class="row">

                {{-- ── Main content ── --}}
                <div class="col-lg-8">

                    <span class="event-status-badge status-{{ $event->status }}">
                        {{ $event->status_label }}
                    </span>

                    <div class="event-cover-wrap mb-6">
                        <img class="event-cover" src="{{ $event->cover_url }}"
                             alt="{{ $event->getTranslation('title', app()->getLocale()) }}">
                    </div>

                    @if($event->getTranslation('description', app()->getLocale()))
                        <div class="event-description">
                            {!! $event->getTranslation('description', app()->getLocale()) !!}
                        </div>
                    @endif

                    {{-- Gallery --}}
                    @if($event->getMedia('gallery')->count())
                        <h3 style="color:#00225a;font-size:20px;margin:30px 0 16px">
                            {{ app()->getLocale() === 'fa' ? 'گالری تصاویر' : 'Photo Gallery' }}
                        </h3>
                        <div class="event-gallery row">
                            @foreach($event->getMedia('gallery') as $media)
                                <div class="col-md-4 col-sm-6 mb-4">
                                    <a href="{{ $media->getUrl() }}" data-fancybox="event-gallery">
                                        <img src="{{ $media->getUrl('thumb') ?? $media->getUrl() }}" alt="gallery">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Videos --}}
                    @if($event->getMedia('videos')->count())
                        <h3 style="color:#00225a;font-size:20px;margin:30px 0 16px">
                            {{ app()->getLocale() === 'fa' ? 'ویدیوها' : 'Videos' }}
                        </h3>
                        <div class="event-videos row">
                            @foreach($event->getMedia('videos') as $media)
                                <div class="col-md-6 mb-4">
                                    <video controls
                                           style="width:100%;background:#000"
                                           @if($media->getUrl('poster')) poster="{{ $media->getUrl('poster') }}" @endif>
                                        <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                                    </video>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>

                {{-- ── Sidebar ── --}}
                <div class="col-lg-4 pt-9 pt-lg-0
                    @if(in_array(app()->getLocale(), ['fa','ar'])) pe-lg-9 @else ps-lg-9 @endif">

                    <div class="event-info-widget mb-6">
                        <h3 class="title">{{ __('messages.events') }}</h3>
                        <ul>
                            @if($event->starts_at)
                                <li>
                                    {{ __('messages.event_start_date') }}
                                    <span>{{ $event->starts_at->format('d M Y') }}</span>
                                </li>
                            @endif
                            @if($event->ends_at)
                                <li>
                                    {{ __('messages.event_end_date') }}
                                    <span>{{ $event->ends_at->format('d M Y') }}</span>
                                </li>
                            @endif
                            @if($event->getTranslation('location', app()->getLocale()))
                                <li>
                                    {{ __('admin.address') }}
                                    <span>{{ $event->getTranslation('location', app()->getLocale()) }}</span>
                                </li>
                            @endif
                            @if($event->city)
                                <li>
                                    {{ __('admin.city') }}
                                    <span>{{ $event->city }}</span>
                                </li>
                            @endif
                            @if($event->country)
                                <li>
                                    {{ __('admin.country') }}
                                    <span>{{ $event->country }}</span>
                                </li>
                            @endif
                            @if($event->hall_number)
                                <li>
                                    {{ __('admin.hall_number') }}
                                    <span>{{ $event->hall_number }}</span>
                                </li>
                            @endif
                            @if($event->booth_number)
                                <li>
                                    {{ __('admin.booth_number') }}
                                    <span>{{ $event->booth_number }}</span>
                                </li>
                            @endif
                        </ul>
                        @if($event->website_url)
                            <a href="{{ $event->website_url }}" target="_blank" rel="noopener noreferrer" class="website-btn">
                                {{ __('messages.event_website') }}
                            </a>
                        @endif
                    </div>

                    <div class="sidebar-cta">
                        <h4>{{ __('messages.any_questions') }}</h4>
                        <a href="{{ route('contact') }}" class="btn btn-custom btn-primary btn-white-hover" style="width:100%">
                            {{ __('messages.contact') }}
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
