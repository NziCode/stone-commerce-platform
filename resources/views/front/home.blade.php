@extends('front.layouts.app')

@section('title', \App\Models\Setting::get('site_name'))

@php
    $locale = app()->getLocale();
    $isRtlLocale = in_array($locale, ['fa', 'ar']);
    $sitePhone = display_phone(\App\Models\Setting::get('site_phone'));
    $aboutYears   = \App\Models\Setting::get('about_years', '25');
    $aboutTitle   = \App\Models\Setting::get('about_title');
    $aboutDesc    = \App\Models\Setting::get('about_desc');
    $heroTitle    = \App\Models\Setting::get('hero_title') ?: $aboutTitle;
    $heroDesc     = \App\Models\Setting::get('hero_desc') ?: $aboutDesc;
    $aboutFeat1   = \App\Models\Setting::get('about_feature_1');
    $aboutFeat2   = \App\Models\Setting::get('about_feature_2');
    $aboutFeat3   = \App\Models\Setting::get('about_feature_3');
    $totalProducts = \App\Models\Product::count();
    $soldProducts  = \App\Models\Product::where('status','sold')->count();
    $totalCategories = \App\Models\Category::count();
    $totalCustomers  = \App\Models\User::role('customer')->count();
@endphp

@push('styles')
<style>
    /* Some element (most likely an un-initialized slider/carousel nav button
       from the base theme) is escaping past the right/left edge of the page,
       which only becomes visible as a horizontal scrollbar at zoom levels
       below 100%. Containing overflow here is the safe, page-scoped fix. */
    html, body { overflow-x: hidden; max-width: 100vw; }
    .swiper-container, .swiper-wrapper { max-width: 100%; }

    /* Categories grid: fixed 4 columns on desktop, tapering down on smaller screens.
       !important is required here because assets/css/style.css is linked AFTER
       @stack('styles') in the layout, so it would otherwise win on source order. */
    .mt-cats {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 1.1rem !important;
    }
    .mt-cat {
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease !important;
    }
    .mt-cat:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px -14px rgba(11,33,71,.25) !important;
        border-color: rgba(255,90,31,.35) !important;
    }
    .mt-cat-ico {
        transition: transform .18s ease !important;
    }
    .mt-cat:hover .mt-cat-ico {
        transform: scale(1.08);
    }
    @media (max-width: 991px) {
        .mt-cats { grid-template-columns: repeat(3, 1fr) !important; }
    }
    @media (max-width: 767px) {
        .mt-cats { grid-template-columns: repeat(2, 1fr) !important; }
    }
    @media (max-width: 479px) {
        .mt-cats { grid-template-columns: repeat(2, 1fr) !important; gap: .7rem !important; }
    }
</style>
@endpush

@section('content')

    {{-- ═══════════════════════════ HERO ═══════════════════════════ --}}
    <section class="mt-hero">
        <div class="mt-container">
            <div class="mt-hero-grid">
                <span class="mt-hero-eyebrow">{{ \App\Models\Setting::get('hero_eyebrow') ?: __('messages.hero_eyebrow') }}</span>

                <h1 class="mt-display">
                    {{ $heroTitle ?: \App\Models\Setting::get('site_name') }}
                </h1>

                <p>{{ Str::limit(strip_tags($heroDesc ?: __('messages.welcome')), 170) }}</p>

                <div class="mt-hero-visual">
                    @if(true)
                        <div class="mt-hero-card">
                            <img src="{{ \App\Models\Setting::get('hero_image') ?: asset('assets/images/hero-default.jpg') }}"
                                 alt="{{ $heroTitle ?: \App\Models\Setting::get('site_name') }}">
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

                <div class="mt-finder">
                    <form action="{{ route('search') }}" method="GET" style="display:flex;flex:1;gap:.4rem">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_placeholder') }}">
                        <button type="submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            {{ __('messages.search') }}
                        </button>
                    </form>
                </div>
                @php
                    $heroKeywords = collect(explode(',', \App\Models\Setting::get('hero_search_keywords', '')))
                        ->map(fn ($k) => trim($k))
                        ->filter()
                        ->take(6);
                @endphp
                <div class="mt-finder-chips">
                    @forelse($heroKeywords as $keyword)
                        <a href="{{ route('search', ['q' => $keyword]) }}">{{ $keyword }}</a>
                    @empty
                        {{-- Fallback until an admin fills in Settings → Hero → Hot Search Keywords --}}
                        @foreach(\App\Models\Category::active()->roots()->ordered()->limit(5)->get() as $chipCat)
                            <a href="{{ route('categories.show', $chipCat->getSlugForLocale($locale)) }}">
                                {{ $chipCat->getTranslation('name', $locale) }}
                            </a>
                        @endforeach
                    @endforelse
                </div>

                <div class="mt-hero-actions" style="margin-top:1.8rem">
                    <a href="{{ route('products.index') }}" class="mt-btn mt-btn-primary">
                        {{ __('messages.all_products') }}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="mt-btn mt-btn-ghost-white">{{ __('messages.contact') }}</a>
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
                    @php
                        $catPalette = ['#ff5a1f', '#0b2147', '#c2410c', '#1e3a8a', '#b45309', '#164e63', '#7c2d12', '#334155'];
                    @endphp
                    @foreach($rootCategories as $cat)
                        @php
                            $catImage = $cat->hasMedia('image')
                                ? $cat->thumb_url
                                : $cat->products()->where('is_active', true)->first()?->main_image_url;
                        @endphp
                        <a href="{{ route('categories.show', $cat->getSlugForLocale($locale)) }}" class="mt-cat">
                            <span class="mt-cat-ico" @if(!$catImage) style="background:{{ $catPalette[$cat->id % count($catPalette)] }}1a;color:{{ $catPalette[$cat->id % count($catPalette)] }}" @endif>
                                @if($catImage)
                                    <img src="{{ $catImage }}" alt="{{ $cat->getTranslation('name', $locale) }}">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="22" height="22">
                                        <path d="M3 16.5V9.8a2 2 0 01.6-1.43l4.9-4.83a2 2 0 012.8 0l4.9 4.83a2 2 0 01.6 1.43v6.7"/>
                                        <path d="M3 16.5 8 20l4-2.3 4 2.3 5-3.5"/>
                                        <path d="M8 9.5l4 2.3 4-2.3"/>
                                    </svg>
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
                    <img src="{{ \App\Models\Setting::get('about_image') ?: asset('assets/images/about-default.jpg') }}"
                         alt="{{ $aboutTitle }}">
                    <div class="mt-about-badge">
                        <strong>{{ $aboutYears }}</strong>
                        <span>{{ __('messages.years_experience') }}</span>
                    </div>
                </div>
                <div>
                    <span class="mt-eyebrow">{{ __('messages.about') }}</span>
                    <div class="mt-vein"><svg viewBox="0 0 84 14"><path d="M1 7c8-10 14 8 22 0s14 8 22 0 14 8 22 0 14 8 16 0"/></svg></div>
                    <h2 class="mt-heading">{{ $aboutTitle }}</h2>
                    <p class="mt-lede" style="margin-top:.9rem">{{ $aboutDesc }}</p>
                    <ul class="mt-about-list">
                        @foreach([$aboutFeat1, $aboutFeat2, $aboutFeat3] as $feat)
                            @if($feat)
                                <li>
                                    <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span>
                                    <span>{{ $feat }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    @if($sitePhone)
                        <a href="tel:{{ $sitePhone }}" class="mt-btn mt-btn-ink" style="margin-top:1.8rem">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <bdi>{{ $sitePhone }}</bdi>
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
                            <div class="mt-arrow project-button-prev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $isRtlLocale ? 'M9 18l6-6-6-6' : 'M15 18l-6-6 6-6' }}"/></svg></div>
                            <div class="mt-arrow project-button-next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $isRtlLocale ? 'M15 18l-6-6 6-6' : 'M9 18l6-6-6-6' }}"/></svg></div>
                        </div>
                    </div>
                    <div class="swiper-container project-slider" style="position:relative;z-index:1">
                        <div class="swiper-wrapper">
                            @foreach($featuredProducts as $product)
                                @php
                                    $cardAttributes = $product->attributes
                                        ->filter(fn($pa) => $pa->attribute?->show_in_card && $pa->attribute?->is_active)
                                        ->sortBy(fn($pa) => $pa->attribute?->sort_order ?? 999);
                                @endphp
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
                                            @if($cardAttributes->isNotEmpty())
                                                <ul class="mt-pcard-attrs">
                                                    @foreach($cardAttributes as $pa)
                                                        <li>
                                                            <span class="mt-pcard-attr-label">
                                                                {{ $pa->attribute->getTranslation('label', $locale, false) ?: $pa->attribute->getTranslation('label', 'en', false) }}:
                                                            </span>
                                                            <span class="mt-pcard-attr-value">{{ $pa->display_value }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            <div class="mt-pcard-foot">
                                                <span class="mt-price">
                                                    @if($product->price_on_request)
                                                        <small>{{ __('messages.price') }}</small>{{ __('messages.price_on_request') }}
                                                    @elseif($product->price)
                                                        {{ number_format($product->price) }} {{ __('messages.currency_rial') }}
                                                        @if($product->price_usd)
                                                            <small>${{ number_format($product->price_usd, 0) }}</small>
                                                        @endif
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
                        <div class="mt-arrow custom-button-prev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $isRtlLocale ? 'M9 18l6-6-6-6' : 'M15 18l-6-6 6-6' }}"/></svg></div>
                        <div class="mt-arrow custom-button-next"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="{{ $isRtlLocale ? 'M15 18l-6-6 6-6' : 'M9 18l6-6-6-6' }}"/></svg></div>
                    </div>
                </div>
                <div class="swiper-container service-slider">
                    <div class="swiper-wrapper">
                        @foreach($latestProducts as $product)
                            @php
                                $cardAttributes = $product->attributes
                                    ->filter(fn($pa) => $pa->attribute?->show_in_card && $pa->attribute?->is_active)
                                    ->sortBy(fn($pa) => $pa->attribute?->sort_order ?? 999);

                                $dimKeys = ['length', 'thickness', 'width'];
                                $dimAttrs = $cardAttributes->filter(fn($pa) => in_array($pa->attribute?->key, $dimKeys));
                                $weightAttr = $cardAttributes->first(fn($pa) => $pa->attribute?->key === 'weight');
                                $otherAttributes = $cardAttributes->reject(
                                    fn($pa) => in_array($pa->attribute?->key, [...$dimKeys, 'weight'])
                                );

                                $dimensionLine = $dimAttrs->isNotEmpty()
                                    ? collect($dimKeys)
                                        ->map(fn($key) => $dimAttrs->first(fn($pa) => $pa->attribute?->key === $key))
                                        ->filter()
                                        ->map(fn($pa) => $pa->value['value'] ?? null)
                                        ->filter(fn($v) => $v !== null && $v !== '')
                                        ->implode(' × ')
                                    : null;

                                $dimensionUnit = optional($dimAttrs->first())->attribute?->unit;
                            @endphp
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
                                        @if($dimensionLine || $weightAttr || $otherAttributes->isNotEmpty())
                                            <ul class="mt-pcard-attrs">
                                                @if($dimensionLine)
                                                    <li>
                                                        <span class="mt-pcard-attr-label">{{ __('messages.dimensions') }}:</span>
                                                        <span class="mt-pcard-attr-value">
                                                            <bdi dir="ltr">{{ $dimensionLine }}{{ $dimensionUnit ? ' ' . $dimensionUnit : '' }}</bdi>
                                                        </span>
                                                    </li>
                                                @endif
                                                @if($weightAttr)
                                                    <li>
                                                        <span class="mt-pcard-attr-label">
                                                            {{ $weightAttr->attribute->getTranslation('label', $locale, false) ?: $weightAttr->attribute->getTranslation('label', 'en', false) }}:
                                                        </span>
                                                        <span class="mt-pcard-attr-value"><bdi dir="ltr">{{ $weightAttr->display_value }}</bdi></span>
                                                    </li>
                                                @endif
                                                @foreach($otherAttributes as $pa)
                                                    <li>
                                                        <span class="mt-pcard-attr-label">
                                                            {{ $pa->attribute->getTranslation('label', $locale, false) ?: $pa->attribute->getTranslation('label', 'en', false) }}:
                                                        </span>
                                                        <span class="mt-pcard-attr-value">{{ $pa->display_value }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <div class="mt-pcard-foot">
                                            <span class="mt-price">
                                                @if($product->price_on_request)
                                                    <small>{{ __('messages.price') }}</small>{{ __('messages.price_on_request') }}
                                                @elseif($product->price)
                                                    {{ number_format($product->price) }} {{ __('messages.currency_rial') }}
                                                    @if($product->price_usd)
                                                        <small>${{ number_format($product->price_usd, 0) }}</small>
                                                    @endif
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
@endpush
