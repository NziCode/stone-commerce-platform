@extends('front.layouts.app')

@section('title', \App\Models\Setting::get('site_name'))

@php
    $locale = app()->getLocale();
    $sitePhone = \App\Models\Setting::get('site_phone');
    $aboutYears   = \App\Models\Setting::get('about_years', '25');
    $aboutTitle   = json_decode(\App\Models\Setting::get('about_title'), true);
    $aboutDesc    = json_decode(\App\Models\Setting::get('about_desc'), true);
    $aboutFeat1   = json_decode(\App\Models\Setting::get('about_feature_1'), true);
    $aboutFeat2   = json_decode(\App\Models\Setting::get('about_feature_2'), true);
    $aboutFeat3   = json_decode(\App\Models\Setting::get('about_feature_3'), true);
    $totalProducts = \App\Models\Product::count();
    $soldProducts  = \App\Models\Product::where('status','sold')->count();
    $totalCategories = \App\Models\Category::count();
    $totalCustomers  = \App\Models\User::role('customer')->count();
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
@endpush

@section('content')

    {{-- ═══════════════════════════ HERO ═══════════════════════════ --}}
    <section class="mt-hero">
        <div class="mt-container">
            <div class="mt-hero-grid">
                <div>
                    <span class="mt-hero-eyebrow">{{ __('messages.hero_eyebrow') }}</span>
                    <h1 class="mt-display">
                        {{ $aboutTitle[$locale] ?? $aboutTitle['en'] ?? \App\Models\Setting::get('site_name') }}
                    </h1>
                    <p>{{ Str::limit(strip_tags($aboutDesc[$locale] ?? $aboutDesc['en'] ?? __('messages.welcome')), 170) }}</p>

                    <div class="mt-finder">
                        <form action="{{ route('search') }}" method="GET" style="display:flex;flex:1;gap:.4rem">
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_placeholder') }}">
                            <button type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                {{ __('messages.search') }}
                            </button>
                        </form>
                    </div>
                    <div class="mt-finder-chips">
                        @foreach(\App\Models\Category::active()->roots()->ordered()->limit(5)->get() as $chipCat)
                            <a href="{{ route('categories.show', $chipCat->getSlugForLocale($locale)) }}">
                                {{ $chipCat->getTranslation('name', $locale) }}
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-hero-actions" style="margin-top:1.8rem">
                        <a href="{{ route('products.index') }}" class="mt-btn mt-btn-primary">
                            {{ __('messages.all_products') }}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                        <a href="{{ route('contact') }}" class="mt-btn mt-btn-ghost-white">{{ __('messages.contact') }}</a>
                    </div>
                </div>

                <div class="mt-hero-visual">
                    @if($featuredProducts->isNotEmpty())
                        <div class="mt-hero-card">
                            <img src="{{ $featuredProducts->first()->medium_image_url }}"
                                 alt="{{ $featuredProducts->first()->getTranslation('name', $locale) }}">
                        </div>
                        <div class="mt-hero-float">
                            <span class="ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                            </span>
                            <div>
                                <strong>{{ $totalProducts }}+</strong>
                                <span>{{ __('messages.products') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- legacy slider kept (hidden visually, JS-driven) for editorial slides if configured --}}
        @if($sliders->isNotEmpty())
            <div class="mt-container" style="margin-top:3rem">
                <div class="swiper-container main-slider" style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-lg)">
                    <div class="swiper-wrapper">
                        @foreach($sliders as $slide)
                            <div class="swiper-slide">
                                <div class="slide-inner bg-height" data-bg-image="{{ $slide->image_url }}" style="position:relative;min-height:300px;display:flex;align-items:center;background-size:cover;background-position:center">
                                    @if($slide->overlay_opacity > 0)
                                        <div style="position:absolute;inset:0;background:{{ $slide->overlay_color ?? '#0b2147' }};opacity:{{ $slide->overlay_opacity / 100 }}"></div>
                                    @endif
                                    <div style="position:relative;z-index:2;padding:2.4rem clamp(1.2rem,4vw,3rem);color:#fff;max-width:640px">
                                        @if($slide->getTranslation('subtitle', $locale))
                                            <span class="mt-eyebrow" style="color:var(--brand-2)">{{ $slide->getTranslation('subtitle', $locale) }}</span>
                                        @endif
                                        @if($slide->getTranslation('title', $locale))
                                            <h2 class="mt-display" style="color:#fff;font-size:clamp(1.5rem,2vw + 1rem,2.4rem);margin:.4rem 0">{!! $slide->getTranslation('title', $locale) !!}</h2>
                                        @endif
                                        @if($slide->getTranslation('description', $locale))
                                            <p style="color:rgba(255,255,255,.85);margin-bottom:1.2rem">{{ $slide->getTranslation('description', $locale) }}</p>
                                        @endif
                                        @if($slide->button_link && $slide->getTranslation('button_text', $locale))
                                            <a class="mt-btn mt-btn-primary" href="{{ $slide->button_link }}" target="{{ $slide->button_target ?? '_self' }}">
                                                {{ $slide->getTranslation('button_text', $locale) }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        @endif
    </section>

    {{-- ═══════════════════════════ STATS FLOAT ═══════════════════════════ --}}
    <div class="mt-container mt-stats-float">
        <div class="mt-stats-card">
            <div class="mt-stat"><strong>{{ $aboutYears }}+</strong><span>{{ __('messages.experience_years') }}</span></div>
            <div class="mt-stat"><strong>{{ $totalProducts }}+</strong><span>{{ __('messages.products') }}</span></div>
            <div class="mt-stat"><strong>{{ $totalCategories }}+</strong><span>{{ __('messages.stone_categories') }}</span></div>
            <div class="mt-stat"><strong>{{ $totalCustomers }}+</strong><span>{{ __('messages.happy_customers') }}</span></div>
        </div>
    </div>

    {{-- ═══════════════════════════ CATEGORIES ═══════════════════════════ --}}
    @if($rootCategories->count())
        <section class="mt-section mt-section--tight">
            <div class="mt-container">
                <div class="mt-section-head">
                    <div>
                        <span class="mt-eyebrow">{{ __('messages.categories') }}</span>
                        <div class="mt-vein"><svg viewBox="0 0 84 14"><path d="M1 7c8-10 14 8 22 0s14 8 22 0 14 8 22 0 14 8 16 0"/></svg></div>
                        <h2 class="mt-heading">{{ __('messages.our_categories') }}</h2>
                    </div>
                    <a href="{{ route('categories.index') }}" class="mt-btn mt-btn-outline mt-btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="mt-cats">
                    @foreach($rootCategories as $cat)
                        <a href="{{ route('categories.show', $cat->getSlugForLocale($locale)) }}" class="mt-cat">
                            <span class="mt-cat-ico">
                                @if($cat->hasMedia('image'))
                                    <img src="{{ $cat->thumb_url }}" alt="{{ $cat->getTranslation('name', $locale) }}">
                                @else
                                    {{ mb_substr($cat->getTranslation('name', $locale), 0, 1) }}
                                @endif
                            </span>
                            <strong>{{ $cat->getTranslation('name', $locale) }}</strong>
                            <span>{{ $cat->products()->count() }} {{ __('messages.products') }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ ABOUT ═══════════════════════════ --}}
    <section class="mt-section" style="background:#fff">
        <div class="mt-container">
            <div class="mt-about">
                <div class="mt-about-media">
                    <img src="{{ \App\Models\Setting::get('about_image') ?: ($featuredProducts->first()->medium_image_url ?? '') }}"
                         alt="{{ $aboutTitle[$locale] ?? '' }}">
                    <div class="mt-about-badge">
                        <strong>{{ $aboutYears }}</strong>
                        <span>{{ __('messages.years_experience') }}</span>
                    </div>
                </div>
                <div>
                    <span class="mt-eyebrow">{{ __('messages.about') }}</span>
                    <div class="mt-vein"><svg viewBox="0 0 84 14"><path d="M1 7c8-10 14 8 22 0s14 8 22 0 14 8 22 0 14 8 16 0"/></svg></div>
                    <h2 class="mt-heading">{{ $aboutTitle[$locale] ?? $aboutTitle['en'] ?? '' }}</h2>
                    <p class="mt-lede" style="margin-top:.9rem">{{ $aboutDesc[$locale] ?? $aboutDesc['en'] ?? '' }}</p>
                    <ul class="mt-about-list">
                        @foreach([$aboutFeat1, $aboutFeat2, $aboutFeat3] as $feat)
                            @if($feat)
                                <li>
                                    <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span>
                                    <span>{{ $feat[$locale] ?? $feat['en'] ?? '' }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    @if($sitePhone)
                        <a href="tel:{{ $sitePhone }}" class="mt-btn mt-btn-ink" style="margin-top:1.8rem">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            {{ $sitePhone }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════ FEATURED PRODUCTS (dark band) ═══════════════════════════ --}}
    @if($featuredProducts->count())
        <section class="mt-section mt-section--tight">
            <div class="mt-container">
                <div class="mt-band mt-on-dark">
                    <div class="mt-section-head" style="margin-bottom:1.6rem">
                        <div>
                            <span class="mt-eyebrow" style="color:var(--brand-2)">{{ __('messages.products') }}</span>
                            <h2 class="mt-heading" style="color:#fff;margin-top:.3rem">{{ __('messages.featured_products') }}</h2>
                        </div>
                        <div class="mt-arrows">
                            <div class="mt-arrow project-button-prev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></div>
                            <div class="mt-arrow project-button-next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                        </div>
                    </div>
                    <div class="swiper-container project-slider" style="position:relative;z-index:1">
                        <div class="swiper-wrapper">
                            @foreach($featuredProducts as $product)
                                <div class="swiper-slide" style="width:280px">
                                    <div class="mt-pcard" style="background:#fff">
                                        <a class="mt-pcard-img" href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">
                                            <img src="{{ $product->medium_image_url }}" alt="{{ $product->getTranslation('name', $locale) }}" loading="lazy">
                                            <span class="mt-pcard-status">{{ $product->status_label }}</span>
                                        </a>
                                        <div class="mt-pcard-body">
                                            <span class="mt-pcard-cat">{{ $product->primaryCategory()?->getTranslation('name', $locale) ?? '' }}</span>
                                            <h3 class="mt-pcard-title">
                                                <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">{{ $product->getTranslation('name', $locale) }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ LATEST PRODUCTS ═══════════════════════════ --}}
    @if($latestProducts->count())
        <section class="mt-section">
            <div class="mt-container">
                <div class="mt-section-head">
                    <div>
                        <span class="mt-eyebrow">{{ __('messages.new') }}</span>
                        <div class="mt-vein"><svg viewBox="0 0 84 14"><path d="M1 7c8-10 14 8 22 0s14 8 22 0 14 8 22 0 14 8 16 0"/></svg></div>
                        <h2 class="mt-heading">{!! __('messages.latest_stones') !!}</h2>
                    </div>
                    <div class="mt-arrows">
                        <div class="mt-arrow custom-button-prev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg></div>
                        <div class="mt-arrow custom-button-next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </div>
                </div>
                <div class="swiper-container service-slider">
                    <div class="swiper-wrapper">
                        @foreach($latestProducts as $product)
                            <div class="swiper-slide" style="width:280px">
                                <div class="mt-pcard">
                                    <a class="mt-pcard-img" href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">
                                        <img src="{{ $product->medium_image_url }}" alt="{{ $product->getTranslation('name', $locale) }}" loading="lazy">
                                        <span class="mt-pcard-status">{{ $product->status_label }}</span>
                                    </a>
                                    <div class="mt-pcard-body">
                                        <span class="mt-pcard-cat">{{ $product->primaryCategory()?->getTranslation('name', $locale) ?? '' }}</span>
                                        <h3 class="mt-pcard-title">
                                            <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">{{ $product->getTranslation('name', $locale) }}</a>
                                        </h3>
                                        <div class="mt-pcard-foot">
                                            <span class="mt-price">
                                                @if($product->price_on_request)
                                                    <small>{{ __('messages.price') }}</small>{{ __('messages.price_on_request') }}
                                                @elseif($product->price)
                                                    {{ number_format($product->price) }}
                                                @endif
                                            </span>
                                            @if($product->isAvailable())
                                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="mt-pcard-add">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ STATS COUNTER ═══════════════════════════ --}}
    <section class="mt-section mt-section--tight" style="background:#fff">
        <div class="mt-container">
            <div class="mt-stats-card" style="box-shadow:none;border:1px solid var(--stone-100)">
                @php
                    $stats = [
                        ['count' => $totalProducts,   'label' => __('messages.products')],
                        ['count' => $soldProducts,    'label' => __('messages.product_sold')],
                        ['count' => $totalCategories, 'label' => __('messages.categories')],
                        ['count' => $totalCustomers,  'label' => __('messages.customers')],
                    ];
                @endphp
                @foreach($stats as $stat)
                    <div class="mt-stat">
                        <strong class="count" data-counterup-time="1500">{{ $stat['count'] }}</strong>
                        <span>{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════ LATEST POSTS ═══════════════════════════ --}}
    @if($latestPosts->count())
        <section class="mt-section">
            <div class="mt-container">
                <div class="mt-section-head">
                    <div>
                        <span class="mt-eyebrow">{{ __('messages.news') }}</span>
                        <div class="mt-vein"><svg viewBox="0 0 84 14"><path d="M1 7c8-10 14 8 22 0s14 8 22 0 14 8 22 0 14 8 16 0"/></svg></div>
                        <h2 class="mt-heading">{{ __('messages.latest_news') }}</h2>
                    </div>
                    <a href="{{ route('posts.index') }}" class="mt-btn mt-btn-outline mt-btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="row g-4">
                    @foreach($latestPosts as $post)
                        <div class="col-md-4">
                            @include('front.components.post-card', ['post' => $post])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ EXHIBITIONS ═══════════════════════════ --}}
    @if($upcomingEvents->count())
        @php $event = $upcomingEvents->first(); @endphp
        <section class="mt-section mt-section--tight">
            <div class="mt-container">
                <div class="mt-about" style="background:var(--ink);border-radius:var(--radius-lg);overflow:hidden;padding:0">
                    <div class="mt-about-media" style="margin:0">
                        <img src="{{ $event->cover_url }}" alt="{{ $event->getTranslation('title', $locale) }}" style="border-radius:0;aspect-ratio:5/4">
                    </div>
                    <div style="padding:2.4rem clamp(1.2rem,3vw,3rem);color:#fff">
                        <span class="mt-eyebrow" style="color:var(--brand-2)">{{ __('messages.events') }}</span>
                        <h2 class="mt-heading" style="color:#fff;margin-top:.4rem">{{ $event->getTranslation('title', $locale) }}</h2>
                        <p style="color:rgba(255,255,255,.75);line-height:1.85;margin:1rem 0">
                            {{ Str::limit($event->getTranslation('description', $locale), 180) }}
                        </p>
                        @if($event->city)
                            <p style="color:rgba(255,255,255,.85);display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $event->getTranslation('location', $locale) }}، {{ $event->city }}
                            </p>
                        @endif
                        @if($event->starts_at)
                            <p style="color:rgba(255,255,255,.85);display:flex;align-items:center;gap:.5rem;margin-bottom:1.4rem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ $event->starts_at->format('d M Y') }}
                                @if($event->ends_at) — {{ $event->ends_at->format('d M Y') }} @endif
                            </p>
                        @endif
                        <a class="mt-btn mt-btn-primary" href="{{ route('events.show', $event->getTranslation('slug', $locale)) }}">
                            {{ __('messages.more_information') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.counterup.js') }}"></script>
@endpush
