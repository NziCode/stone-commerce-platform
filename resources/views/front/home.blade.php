@extends('front.layouts.app')

@section('title', \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
@endpush

@section('content')

    {{-- ═══ Slider ═══ --}}
    @if($sliders->count())
        <div class="slider-area">
            <div class="swiper-container main-slider swiper-arrow with-bg_white">
                <div class="swiper-wrapper">
                    @foreach($sliders as $slide)
                        <div class="swiper-slide animation-style-01">
                            <div class="slide-inner bg-height"
                                 @if($slide->type === 'image' && $slide->getFirstMediaUrl('image'))
                                     data-bg-image="{{ $slide->getFirstMediaUrl('image', 'optimized') }}"
                                 @elseif($slide->image)
                                     data-bg-image="{{ asset($slide->image) }}"
                                 @else
                                     data-bg-image="{{ asset('assets/images/slider/bg/1-1.jpg') }}"
                                @endif
                            >
                                @if($slide->type === 'video' && $slide->getFirstMediaUrl('video'))
                                    <video class="w-100 h-100" style="object-fit:cover;position:absolute;top:0;left:0;"
                                           autoplay muted loop playsinline>
                                        <source src="{{ $slide->getFirstMediaUrl('video') }}" type="video/mp4">
                                    </video>
                                @endif
                                <div class="container">
                                    <div class="slide-content {{ in_array(app()->getLocale(), ['fa','ar']) ? '' : 'text-white' }}">
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
                                        @if($slide->button_link && $slide->getTranslation('button_text', app()->getLocale()))
                                            <div class="button-wrap">
                                                <a class="btn btn-custom btn-primary btn-white-hover"
                                                   href="{{ $slide->button_link }}"
                                                   target="{{ $slide->button_target }}">
                                                    {{ $slide->getTranslation('button_text', app()->getLocale()) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination with-bg d-md-none"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    @endif

    {{-- ═══ Banner (دسته‌بندی‌های اصلی) ═══ --}}
    @if($rootCategories->count())
        <div class="banner pt-140">
            <div class="container">
                <div class="row g-lg-9">
                    @foreach($rootCategories->take(3) as $category)
                        <div class="col-lg-4 col-md-6 {{ !$loop->first ? 'pt-6 pt-md-0' : '' }}">
                            <a href="{{ route('categories.show', $category->getTranslation('slug', app()->getLocale())) }}"
                               class="banner-item text-white d-block"
                               @if($category->getFirstMediaUrl('image'))
                                   data-bg-image="{{ $category->getFirstMediaUrl('image') }}"
                               @else
                                   data-bg-image="{{ asset('assets/images/banner/inner-bg/1-' . ($loop->index + 1) . '.png') }}"
                                @endif
                            >
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

    {{-- ═══ Featured Products (بجای Project) ═══ --}}
    @if($featuredProducts->count())
        <div class="project-area">
            <div class="project-inner" data-bg-image="{{ asset('assets/images/project/bg/1-1.png') }}">
                <div class="button-wrap text-end">
                    <a class="btn btn-project" href="{{ route('products.index') }}">
                        <span>مشاهده همه</span>
                    </a>
                </div>
                <div class="container-fluid p-0">
                    <div class="project-with-title">
                        <div class="section-title-area text-white h-100">
                            <div class="title-with-arrow">
                                <div class="section-title-wrap style-02">
                                    <div class="section-title">
                                        <span>محصولات ویژه</span>
                                        <h2 class="mb-0">جدیدترین <br> سنگ‌های ما</h2>
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
                                            {{ $product->primaryCategory()?->getTranslation('name', app()->getLocale()) ?? 'سنگ' }}
                                        </span>
                                                <h3 class="title mb-0">
                                                    <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                                        {{ $product->getTranslation('name', app()->getLocale()) }}
                                                    </a>
                                                </h3>
                                                <span class="{{ $product->status === 'available' ? 'text-success' : 'text-danger' }}">
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

    {{-- ═══ Latest Products (بجای Service) ═══ --}}
    @if($latestProducts->count())
        <div class="service-area py-140">
            <div class="container">
                <div class="section-title-area pb-70">
                    <div class="section-title with-border pb-5 pb-lg-0">
                        <span>محصولات</span>
                        <h2 class="mb-0 font-size-50">جدیدترین <br> سنگ‌های موجود</h2>
                    </div>
                    <div class="section-banner text-white align-self-center p-7"
                         data-bg-image="{{ asset('assets/images/service/bg/1-1.png') }}">
                        <h2 class="info mb-0">
                            سوال دارید؟
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
                        ['count' => \App\Models\Product::count(), 'label' => 'محصولات'],
                        ['count' => \App\Models\Product::where('status','sold')->count(), 'label' => 'فروخته شده'],
                        ['count' => \App\Models\Category::count(), 'label' => 'دسته‌بندی'],
                        ['count' => \App\Models\User::role('customer')->count(), 'label' => 'مشتریان'],
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

    {{-- ═══ Blog / اخبار ═══ --}}
    @if($latestPosts->count())
        <div class="blog-area py-140">
            <div class="container">
                <div class="section-title-area style-01 pb-70">
                    <div class="section-title-wrap">
                        <div class="section-title with-border different-width text-start text-lg-end">
                            <span>اخبار</span>
                            <h2 class="mb-0">آخرین اخبار</h2>
                        </div>
                        <div class="section-desc">
                            <p class="font-size-20 mb-0">آخرین اخبار و رویدادهای صنعت سنگ</p>
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
                                                            ادامه مطلب
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

    {{-- ═══ نمایشگاه‌های آینده ═══ --}}
    @if($upcomingEvents->count())
        <div class="banner-style-2 position-relative"
             data-bg-image="{{ asset('assets/images/banner/bg/2-1.png') }}">
            <div class="container-fluid p-0 overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="banner-img">
                            <img src="{{ $upcomingEvents->first()->cover_url }}" alt="نمایشگاه">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="banner-with-sticker">
                            <div class="banner-content text-white">
                                <span>نمایشگاه‌ها</span>
                                <h2 class="title mb-7">{{ $upcomingEvents->first()->getTranslation('title', app()->getLocale()) }}</h2>
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
                                        اطلاعات بیشتر
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
