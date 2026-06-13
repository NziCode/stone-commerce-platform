@extends('front.layouts.app')

@section('title', \App\Models\Setting::get('site_name'))

@php
    $sitePhone = \App\Models\Setting::get('site_phone');
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
    <style>
        /* Featured Products section redesign */
        .featured-products-area {
            background-color: #0a2756;
            overflow: hidden;
            margin: 0 auto;
            max-width: 1600px;
        }
        @media (min-width: 1600px) {
            .featured-products-area {
                margin-left: 1.5rem;
                margin-right: 1.5rem;
            }
        }
        .featured-products-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .featured-products-header .sub-title {
            display: block;
            color: #ff5e13;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .featured-products-header h2 {
            color: #fff;
            font-size: 28px;
            margin: 0;
        }
        .featured-products-arrows {
            display: flex;
            gap: 10px;
        }
        .featured-products-arrows > div {
            width: 46px;
            height: 46px;
            border: 1px solid rgba(255,255,255,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .featured-products-arrows > div:hover {
            background-color: #ff5e13;
            border-color: #ff5e13;
        }
        .featured-products-area .project-slider {
            overflow: hidden;
        }
        .featured-products-area .project-slider .swiper-slide {
            width: 320px;
            flex-shrink: 0;
        }
        .featured-products-area .project-item,
        .featured-products-area .project-img {
            display: block;
            width: 100%;
            height: 320px;
            position: relative;
        }
        .featured-products-area .project-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }
        .featured-products-area .project-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            width: auto;
            background-color: rgba(255,255,255,0.95);
            padding: 14px 20px;
            text-align: start;
            opacity: 1;
            visibility: visible;
            box-shadow: none;
        }
        .featured-products-area .project-content .sub-title {
            color: #ff5e13;
            font-size: 12px;
            display: block;
            margin-bottom: 2px;
        }
        .featured-products-area .project-content h3 {
            font-size: 16px;
            margin: 0;
        }
        .featured-products-area .project-content h3 a {
            color: #00225a;
        }
        [dir="rtl"] .featured-products-arrows {
            flex-direction: row-reverse;
        }
    </style>
@endpush

@section('content')

    {{-- ═══ Slider ═══ --}}
    <div class="slider-area">
        <div class="swiper-container main-slider swiper-arrow with-bg_white">
            <div class="swiper-wrapper">
                @forelse($sliders as $slide)
                    <div class="swiper-slide animation-style-01">
                        <div class="slide-inner bg-height"
                             data-bg-image="{{ $slide->image_url }}">
                            @if($slide->overlay_opacity > 0)
                                <div style="position:absolute;top:0;left:0;width:100%;height:100%;
                                            background:{{ $slide->overlay_color ?? '#000000' }};
                                            opacity:{{ $slide->overlay_opacity / 100 }};
                                            z-index:1"></div>
                            @endif
                            <div class="container" style="position:relative;z-index:2">
                                <div class="slide-content">
                                    @if($slide->getTranslation('subtitle', app()->getLocale()))
                                        <span class="sub-title mb-1">
                                            {{ $slide->getTranslation('subtitle', app()->getLocale()) }}
                                        </span>
                                    @endif
                                    @if($slide->getTranslation('title', app()->getLocale()))
                                        <h2 class="title mb-3">
                                            {!! $slide->getTranslation('title', app()->getLocale()) !!}
                                        </h2>
                                    @endif
                                    @if($slide->getTranslation('description', app()->getLocale()))
                                        <p class="short-desc-2 font-size-20 mb-7">
                                            {{ $slide->getTranslation('description', app()->getLocale()) }}
                                        </p>
                                    @endif
                                    @if($slide->button_link && $slide->getTranslation('button_text', app()->getLocale()))
                                        <div class="button-wrap">
                                            <a class="btn btn-custom btn-secondary btn-white-hover me-3"
                                               href="{{ $slide->button_link }}"
                                               target="{{ $slide->button_target ?? '_self' }}">
                                                {{ $slide->getTranslation('button_text', app()->getLocale()) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide animation-style-01">
                        <div class="slide-inner bg-height"
                             style="background:linear-gradient(135deg,#00225a,#ff5e13)">
                            <div class="container">
                                <div class="slide-content">
                                    <span class="sub-title mb-1">{{ __('messages.welcome') }}</span>
                                    <h2 class="title mb-3">EN Trading Group</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination with-bg d-md-none"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    {{-- ═══ Banner ═══ --}}
    @php
        $locale = app()->getLocale();
        $banners = [];
        for ($i = 1; $i <= 3; $i++) {
            $title = json_decode(\App\Models\Setting::get("banner_{$i}_title"), true);
            $desc  = json_decode(\App\Models\Setting::get("banner_{$i}_desc"), true);
            $banners[] = [
                'title' => $title[$locale] ?? $title['en'] ?? '',
                'desc'  => $desc[$locale]  ?? $desc['en']  ?? '',
                'image' => asset("assets/images/banner/inner-bg/1-{$i}.png"),
            ];
        }
    @endphp
    <div class="banner pt-140">
        <div class="container">
            <div class="row g-lg-9">
                @foreach($banners as $index => $banner)
                    <div class="col-lg-4 col-md-6 {{ $index > 0 ? 'pt-6 pt-md-0' : '' }}">
                        <div class="banner-item text-white d-block"
                             data-bg-image="{{ $banner['image'] }}">
                            <div class="banner-content">
                                <h3 class="title mb-3">{{ $banner['title'] }}</h3>
                                @if($banner['desc'])
                                    <p class="short-desc mb-0">{{ $banner['desc'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ About ═══ --}}
    @php
        $locale = app()->getLocale();
        $aboutYears   = \App\Models\Setting::get('about_years', '25');
        $aboutTitle   = json_decode(\App\Models\Setting::get('about_title'), true);
        $aboutDesc    = json_decode(\App\Models\Setting::get('about_desc'), true);
        $aboutFeat1   = json_decode(\App\Models\Setting::get('about_feature_1'), true);
        $aboutFeat2   = json_decode(\App\Models\Setting::get('about_feature_2'), true);
        $aboutFeat3   = json_decode(\App\Models\Setting::get('about_feature_3'), true);
    @endphp
    <div class="about-area about-style-2 py-130">
        <div class="container">
            <div class="section-title-area style-01 pb-70">
                <div class="section-title-wrap">
                    <div class="section-title with-border text-lg-end">
                        <span>{{ __('messages.about') }}</span>
                        <h2 class="mb-0">{{ $aboutTitle[$locale] ?? $aboutTitle['en'] ?? '' }}</h2>
                    </div>
                    <div class="section-desc">
                        <p class="font-size-20 mb-0">{{ $aboutDesc[$locale] ?? $aboutDesc['en'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-img-wrap">
                        <div class="about-pattern">
                            <img src="{{ asset('assets/images/about/pattern.png') }}" alt="Pattern">
                        </div>
                        <div class="about-img">
                            <img class="img-full"
                                 src="{{ \App\Models\Setting::get('about_image') ?: asset('assets/images/about/1-1.jpg') }}"
                                 alt="{{ $aboutTitle[$locale] ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="about-content">
                        <div class="experience style-2 text-primary">
                            <div class="experience-content">
                                <span class="year">{{ $aboutYears }}</span>
                                <h2 class="our-progress">{{ __('messages.years_experience') }}</h2>
                            </div>
                            <div class="experience-img">
                                <img src="{{ asset('assets/images/about/avatar.png') }}" alt="Avatar">
                            </div>
                        </div>
                        <h3 class="sub-title mb-4">{{ $aboutTitle[$locale] ?? $aboutTitle['en'] ?? '' }}</h3>
                        <p class="short-desc mb-7">{{ $aboutDesc[$locale] ?? $aboutDesc['en'] ?? '' }}</p>
                        <ul class="list-item">
                            @foreach([$aboutFeat1, $aboutFeat2, $aboutFeat3] as $feat)
                                @if($feat)
                                    <li>
                                        <div class="list-icon">
                                            <i class="fa fa-check"></i>
                                        </div>
                                        <div class="list-text">
                                            <span>{{ $feat[$locale] ?? $feat['en'] ?? '' }}</span>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ Featured Products ═══ --}}
    @if($featuredProducts->count())
        <div class="featured-products-area py-3 px-3 mt-5">
            <div class="featured-products-header">
                <div>
                    <span class="sub-title">{{ __('messages.products') }}</span>
                    <h2>{{ __('messages.featured_products') }}</h2>
                </div>
                <div class="featured-products-arrows">
                    <div class="project-button-prev">
                        <i class="ion-chevron-left"></i>
                    </div>
                    <div class="project-button-next">
                        <i class="ion-chevron-right"></i>
                    </div>
                </div>
            </div>
            <div class="swiper-container project-slider">
                <div class="swiper-wrapper">
                    @foreach($featuredProducts as $product)
                        <div class="swiper-slide">
                            <div class="project-item">
                                <a class="project-img"
                                   href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                    <img src="{{ $product->medium_image_url }}"
                                         alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                </a>
                                <div class="project-content">
                                    <span class="sub-title">
                                        {{ $product->primaryCategory()?->getTranslation('name', app()->getLocale()) ?? '' }}
                                    </span>
                                    <h3 class="mb-0">
                                        <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                            {{ $product->getTranslation('name', app()->getLocale()) }}
                                        </a>
                                    </h3>
                                    <span class="{{ $product->status === 'available' ? 'text-success' : 'text-warning' }}" style="font-size:12px">
                                        {{ $product->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ═══ Latest Products ═══ --}}
    @if($latestProducts->count())
        <div class="service-area py-140">
            <div class="container">
                <div class="section-title-area pb-70">
                    <div class="section-title with-border pb-5 pb-lg-0">
                        <span>{{ __('messages.products') }}</span>
                        <h2 class="mb-0 font-size-50">{!! __('messages.latest_stones') !!}</h2>
                    </div>
                    <div class="section-banner text-white align-self-center p-7"
                         data-bg-image="{{ asset('assets/images/service/bg/1-1.png') }}">
                        <h2 class="info mb-0">
                            {{ __('messages.any_questions') }}
                            @if($sitePhone)
                                <span>{{ $sitePhone }}</span>
                            @else
                                <span>02433467247</span>
                            @endif
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="custom-arrow-holder position-relative">
                            <div class="custom-button-wrap d-none d-md-flex">
                                <div class="custom-button-prev">
                                    <i class="ion-chevron-left"></i>
                                </div>
                                <div class="custom-button-next">
                                    <i class="ion-chevron-right"></i>
                                </div>
                            </div>
                            <div class="swiper-container service-slider swiper-arrow with-bg_white">
                                <div class="swiper-wrapper">
                                    @foreach($latestProducts as $product)
                                        <div class="swiper-slide">
                                            <div class="service-item">
                                                <div class="service-img">
                                                    <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                                        <img src="{{ $product->medium_image_url }}"
                                                             alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                                    </a>
                                                    <div class="add-action text-white">
                                                        <h2 class="title mb-0">
                                                            <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                                                {{ $product->getTranslation('name', app()->getLocale()) }}
                                                            </a>
                                                        </h2>
                                                        <div class="icon">
                                                            @if($product->isAvailable())
                                                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" style="background:none;border:none;color:white">
                                                                        <i class="ion-ios-plus-empty"></i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="text-warning" style="font-size:14px">
                                                                    {{ $product->status_label }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══ Stats Counter ═══ --}}
    <div class="counter-area pt-140">
        <div class="container">
            <div class="row">
                @php
                    $stats = [
                        ['count' => \App\Models\Product::count(),                        'label' => __('messages.products')],
                        ['count' => \App\Models\Product::where('status','sold')->count(), 'label' => __('messages.product_sold')],
                        ['count' => \App\Models\Category::count(),                       'label' => __('messages.categories')],
                        ['count' => \App\Models\User::role('customer')->count(),          'label' => __('messages.customers')],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-item">
                            <h3 class="count mb-0" data-counterup-time="1500">{{ $stat['count'] }}</h3>
                            <h2 class="count-inner-text mb-0">{{ $stat['count'] }}</h2>
                            <h4 class="count-title mb-0">{{ $stat['label'] }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ Latest Posts ═══ --}}
    @if($latestPosts->count())
        <div class="blog-area py-140">
            <div class="container">
                <div class="section-title-area style-01 pb-70">
                    <div class="section-title-wrap">
                        <div class="section-title with-border different-width text-start text-lg-end">
                            <span>{{ __('messages.news') }}</span>
                            <h2 class="mb-0">{{ __('messages.latest_news') }}</h2>
                        </div>
                        <div class="section-desc">
                            <p class="font-size-20 mb-0">{{ __('messages.latest_news_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="swiper-container blog-slider">
                            <div class="swiper-wrapper">
                                @foreach($latestPosts as $post)
                                    <div class="swiper-slide">
                                        <div class="blog-item">
                                            <a class="blog-img"
                                               href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                                                <img class="img-full" src="{{ $post->cover_url }}"
                                                     alt="{{ $post->getTranslation('title', app()->getLocale()) }}">
                                            </a>
                                            <div class="blog-content">
                                                <span class="blog-meta">
                                                    {{ $post->author?->name }}
                                                    &nbsp;—&nbsp;
                                                    {{ $post->published_at?->format('d M Y') }}
                                                </span>
                                                <h3 class="title mb-2">
                                                    <a href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                                                        {{ $post->getTranslation('title', app()->getLocale()) }}
                                                    </a>
                                                </h3>
                                                <p class="mb-4">
                                                    {{ Str::limit($post->getTranslation('excerpt', app()->getLocale()), 100) }}
                                                </p>
                                                <ul class="blog-button-wrap">
                                                    <li>
                                                        <a class="btn btn-link p-0"
                                                           href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                                                            {{ __('messages.read_more') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="blog-pagination d-md-none"></div>
                            <div class="blog-button-next"></div>
                            <div class="blog-button-prev"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══ Upcoming Events ═══ --}}
    @if($upcomingEvents->count())
        <div class="banner-style-2 position-relative"
             data-bg-image="{{ asset('assets/images/banner/bg/2-1.png') }}">
            <div class="container-fluid p-0 overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="banner-img">
                            <img src="{{ $upcomingEvents->first()->cover_url }}"
                                 alt="{{ $upcomingEvents->first()->getTranslation('title', app()->getLocale()) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="banner-with-sticker">
                            <div class="banner-content text-white">
                                <span>{{ __('messages.events') }}</span>
                                <h2 class="title mb-7">
                                    {{ $upcomingEvents->first()->getTranslation('title', app()->getLocale()) }}
                                </h2>
                                <p class="desc font-size-20 mb-8">
                                    {{ $upcomingEvents->first()->getTranslation('description', app()->getLocale()) }}
                                </p>
                                @if($upcomingEvents->first()->city)
                                    <p class="mb-4">
                                        <i class="fa fa-map-marker me-2"></i>
                                        {{ $upcomingEvents->first()->getTranslation('location', app()->getLocale()) }}
                                        ، {{ $upcomingEvents->first()->city }}
                                    </p>
                                @endif
                                @if($upcomingEvents->first()->starts_at)
                                    <p class="mb-6">
                                        <i class="fa fa-calendar me-2"></i>
                                        {{ $upcomingEvents->first()->starts_at->format('d M Y') }}
                                        @if($upcomingEvents->first()->ends_at)
                                            — {{ $upcomingEvents->first()->ends_at->format('d M Y') }}
                                        @endif
                                    </p>
                                @endif
                                <div class="button-wrap">
                                    <a class="btn btn-custom btn-primary btn-white-hover"
                                       href="{{ route('events.show', $upcomingEvents->first()->getTranslation('slug', app()->getLocale())) }}">
                                        {{ __('messages.more_information') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.counterup.js') }}"></script>
@endpush
