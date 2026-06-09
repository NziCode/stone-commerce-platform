@extends('front.layouts.app')

@section('title', \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
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
                            {{-- Overlay --}}
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
                                            {{ $slide->getTranslation('subtitle', app()->getLocale()) }}
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

    {{-- ═══ Banner (دسته‌بندی‌های اصلی) ═══ --}}
    @if($rootCategories->count())
        <div class="banner pt-140">
            <div class="container">
                <div class="row g-lg-9">
                    @foreach($rootCategories->take(3) as $category)
                        <div class="col-lg-4 col-md-6 {{ !$loop->first ? 'pt-6 pt-md-0' : '' }}">
                            <a href="{{ route('categories.show', $category->getTranslation('slug', app()->getLocale())) }}"
                               class="banner-item text-white d-block"
                               data-bg-image="{{ $category->getFirstMediaUrl('image') ?: asset('assets/images/banner/inner-bg/1-' . ($loop->index + 1) . '.png') }}">
                                <div class="banner-content">
                                    <h3 class="title mb-3">
                                        {{ $category->getTranslation('name', app()->getLocale()) }}
                                    </h3>
                                    @if($category->getTranslation('excerpt', app()->getLocale()))
                                        <p class="short-desc mb-0">
                                            {{ $category->getTranslation('excerpt', app()->getLocale()) }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ═══ Featured Products ═══ --}}
    @if($featuredProducts->count())
        <div class="project-area">
            <div class="project-inner" data-bg-image="{{ asset('assets/images/project/bg/1-1.png') }}">
                <div class="button-wrap text-end">
                    <a class="btn btn-project" href="{{ route('products.index') }}">
                        <span>{{ __('messages.products') }}</span>
                    </a>
                </div>
                <div class="container-fluid p-0">
                    <div class="project-with-title">
                        <div class="section-title-area text-white h-100">
                            <div class="title-with-arrow">
                                <div class="section-title-wrap style-02">
                                    <div class="section-title">
                                        <span>{{ __('messages.products') }}</span>
                                        <h2 class="mb-0">
                                            @if(app()->getLocale() === 'fa') جدیدترین <br> سنگ‌های ما
                                            @elseif(app()->getLocale() === 'ar') أحدث <br> أحجارنا
                                            @elseif(app()->getLocale() === 'hi') हमारे नवीनतम <br> पत्थर
                                            @elseif(app()->getLocale() === 'it') Le Nostre <br> Ultime Pietre
                                            @else Our Latest <br> Stones
                                            @endif
                                        </h2>
                                    </div>
                                </div>
                                <div class="project-button-wrap">
                                    <div class="project-button-prev">
                                        <i class="ion-chevron-left"></i>
                                    </div>
                                    <div class="project-button-next">
                                        <i class="ion-chevron-right"></i>
                                    </div>
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
                                                <h3 class="title mb-0">
                                                    <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                                        {{ $product->getTranslation('name', app()->getLocale()) }}
                                                    </a>
                                                </h3>
                                                <span class="{{ $product->status === 'available' ? 'text-success' : 'text-warning' }}">
                                                    {{ $product->status_label }}
                                                </span>
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
    @endif

    {{-- ═══ Latest Products ═══ --}}
    @if($latestProducts->count())
        <div class="service-area py-140">
            <div class="container">
                <div class="section-title-area pb-70">
                    <div class="section-title with-border pb-5 pb-lg-0">
                        <span>{{ __('messages.products') }}</span>
                        <h2 class="mb-0 font-size-50">
                            @if(app()->getLocale() === 'fa') جدیدترین <br> سنگ‌های موجود
                            @elseif(app()->getLocale() === 'ar') أحدث <br> الأحجار المتاحة
                            @elseif(app()->getLocale() === 'hi') नवीनतम <br> उपलब्ध पत्थर
                            @elseif(app()->getLocale() === 'it') Le Ultime <br> Pietre Disponibili
                            @else Latest <br> Available Stones
                            @endif
                        </h2>
                    </div>
                    <div class="section-banner text-white align-self-center p-7"
                         data-bg-image="{{ asset('assets/images/service/bg/1-1.png') }}">
                        <h2 class="info mb-0">
                            @if(app()->getLocale() === 'fa') سوال دارید؟
                            @elseif(app()->getLocale() === 'ar') هل لديك سؤال؟
                            @elseif(app()->getLocale() === 'hi') कोई प्रश्न?
                            @elseif(app()->getLocale() === 'it') Hai domande?
                            @else Any Questions?
                            @endif
                            <span>{{ \App\Models\Setting::get('site_phone') }}</span>
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
                        ['count' => \App\Models\Product::count(), 'label' => __('messages.products')],
                        ['count' => \App\Models\Product::where('status','sold')->count(), 'label' => __('messages.product_sold')],
                        ['count' => \App\Models\Category::count(), 'label' => __('messages.categories')],
                        ['count' => \App\Models\User::role('customer')->count(), 'label' => app()->getLocale() === 'fa' ? 'مشتریان' : (app()->getLocale() === 'ar' ? 'العملاء' : (app()->getLocale() === 'it' ? 'Clienti' : (app()->getLocale() === 'hi' ? 'ग्राहक' : 'Customers')))],
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
                            <h2 class="mb-0">
                                @if(app()->getLocale() === 'fa') آخرین اخبار
                                @elseif(app()->getLocale() === 'ar') آخر الأخبار
                                @elseif(app()->getLocale() === 'hi') नवीनतम समाचार
                                @elseif(app()->getLocale() === 'it') Ultime Notizie
                                @else Latest News
                                @endif
                            </h2>
                        </div>
                        <div class="section-desc">
                            <p class="font-size-20 mb-0">
                                @if(app()->getLocale() === 'fa') آخرین اخبار و رویدادهای صنعت سنگ
                                @elseif(app()->getLocale() === 'ar') آخر أخبار وأحداث صناعة الحجر
                                @elseif(app()->getLocale() === 'hi') पत्थर उद्योग की नवीनतम खबरें
                                @elseif(app()->getLocale() === 'it') Ultime notizie del settore lapideo
                                @else Latest news and events in the stone industry
                                @endif
                            </p>
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
                                                            @if(app()->getLocale() === 'fa') ادامه مطلب
                                                            @elseif(app()->getLocale() === 'ar') اقرأ المزيد
                                                            @elseif(app()->getLocale() === 'hi') और पढ़ें
                                                            @elseif(app()->getLocale() === 'it') Leggi di più
                                                            @else Read More
                                                            @endif
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
                                        @if(app()->getLocale() === 'fa') اطلاعات بیشتر
                                        @elseif(app()->getLocale() === 'ar') معلومات أكثر
                                        @elseif(app()->getLocale() === 'hi') अधिक जानकारी
                                        @elseif(app()->getLocale() === 'it') Maggiori Informazioni
                                        @else More Information
                                        @endif
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
