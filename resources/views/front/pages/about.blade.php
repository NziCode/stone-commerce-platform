@extends('front.layouts.app')

@section('title', $page->getTranslation('title', $locale) . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    {{-- Scroll-reveal is opt-in: content stays visible if this script or about.js never runs. --}}
    <script>
        document.documentElement.classList.add('ab-js');
        window.__abFallback = setTimeout(function () { document.documentElement.classList.remove('ab-js'); }, 4000);
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}?v={{ @filemtime(public_path('assets/css/about.css')) ?: 1 }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/about.js') }}?v={{ @filemtime(public_path('assets/js/about.js')) ?: 1 }}" defer></script>
@endpush

@section('content')
    @php
        $siteName   = \App\Models\Setting::get('site_name');
        $isFa       = $locale === 'fa';
        $num        = fn ($n) => $isFa ? \App\Support\Jalali::toPersianDigits($n) : $n;
        $title      = $page->getTranslation('title', $locale);
        $excerpt    = $page->getTranslation('excerpt', $locale);
        $aboutTitle = \App\Models\Setting::get('about_title');
        $storyHtml  = $page->getTranslation('content', $locale);
        $icons = [
            '<path d="m3 20 6-12 4 6 3-4 5 10z"/>',
            '<path d="M12 2 4 6v6c0 5 3.5 9 8 10 4.5-1 8-5 8-10V6l-8-4z"/><path d="m9 12 2 2 4-4"/>',
            '<path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>',
        ];
        $arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"' . (in_array($locale, ['fa', 'ar']) ? ' style="transform:scaleX(-1)"' : '') . '><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
    @endphp

    {{-- ═══════════════════════════ HERO ═══════════════════════════ --}}
    <section class="ab-hero" @if($hero) style="--ab-hero:url('{{ $hero }}')" @endif>
        <div class="ab-hero-bg" aria-hidden="true"></div>
        <svg class="ab-veins" viewBox="0 0 1200 600" preserveAspectRatio="none" aria-hidden="true">
            <path d="M-20 430 C 200 350, 380 530, 620 440 S 980 340, 1220 460"/>
            <path d="M-20 510 C 240 440, 460 600, 720 510 S 1000 430, 1220 530"/>
            <path d="M-20 350 C 180 280, 420 410, 660 340 S 960 260, 1220 360"/>
        </svg>

        <div class="mt-container ab-hero-inner">
            <div class="ab-hero-copy">
                <ul class="mt-crumbs ab-crumbs">
                    <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                    <li><span class="is-active">{{ $title }}</span></li>
                </ul>
                <span class="ab-eyebrow">{{ $siteName }}</span>
                <h1 class="ab-title">{{ $title }}</h1>
                @if($excerpt)
                    <p class="ab-lead">{{ $excerpt }}</p>
                @endif
                <div class="ab-actions">
                    <a href="{{ route('products.index') }}" class="mt-btn mt-btn-primary">{{ __('messages.ab_cta_products') }} {!! $arrow !!}</a>
                    <a href="{{ route('contact') }}" class="mt-btn mt-btn-ghost-white">{{ __('messages.contact') }}</a>
                </div>
            </div>

            @if($years)
                <div class="ab-seal" aria-hidden="true">
                    <svg viewBox="0 0 200 200" class="ab-seal-ring">
                        <defs><path id="abSealPath" d="M100,100 m-82,0 a82,82 0 1,1 164,0 a82,82 0 1,1 -164,0"/></defs>
                        <text>
                            <textPath href="#abSealPath" startOffset="0">{{ \Illuminate\Support\Str::upper($siteName) }} • {{ \Illuminate\Support\Str::upper($siteName) }} •</textPath>
                        </text>
                    </svg>
                    <div class="ab-seal-core">
                        <strong>{{ $num($years) }}+</strong>
                        <span>{{ __('messages.ab_years_label') }}</span>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════ STORY ═══════════════════════════ --}}
    <section class="mt-section ab-story">
        <div class="mt-container ab-story-grid">
            <div class="ab-story-copy" data-ab-reveal>
                <span class="mt-eyebrow">{{ __('messages.ab_story_kicker') }}</span>
                @if($aboutTitle)
                    <h2 class="mt-heading ab-h2">{{ $aboutTitle }}</h2>
                @endif
                <div class="mt-prose ab-prose">{!! $storyHtml !!}</div>
            </div>

            @if(count($story))
                <div class="ab-collage ab-collage-{{ count($story) }}" data-ab-reveal>
                    <span class="ab-stripes" aria-hidden="true"></span>
                    @foreach($story as $i => $url)
                        <figure class="ab-shot ab-shot-{{ $i + 1 }}">
                            <img src="{{ $url }}" alt="{{ $title }}" loading="lazy" decoding="async">
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════ NUMBERS ═══════════════════════════ --}}
    @if(count($stats))
        <section class="ab-stats" aria-label="{{ $title }}">
            <div class="mt-container">
                <div class="ab-stats-grid">
                    @foreach($stats as $stat)
                        <div class="ab-stat" data-ab-reveal>
                            <strong><span data-ab-count="{{ $stat['value'] }}">{{ $num($stat['value']) }}</span>{{ $stat['suffix'] }}</strong>
                            <span>{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ OWNER ═══════════════════════════ --}}
    @if($founder)
        <section class="mt-section ab-founder">
            <div class="mt-container">
                @include('front.pages.partials.founder-card', ['founder' => $founder, 'locale' => $locale])
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ WHY US ═══════════════════════════ --}}
    @if(count($pillars))
        <section class="mt-section ab-pillars">
            <div class="mt-container">
                <div class="ab-head" data-ab-reveal>
                    <span class="mt-eyebrow">{{ __('messages.ab_why_kicker') }}</span>
                    <h2 class="mt-heading">{{ __('messages.ab_why_title') }}</h2>
                </div>
                <div class="ab-pillars-grid">
                    @foreach($pillars as $i => $pillar)
                        <article class="ab-pillar" data-ab-reveal style="--ab-delay:{{ $i * 90 }}ms">
                            <span class="ab-pillar-no" aria-hidden="true">{{ $num('0' . ($i + 1)) }}</span>
                            <span class="ab-pillar-ico" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$i % 3] !!}</svg>
                            </span>
                            @if($pillar['title'])
                                <h3>{{ $pillar['title'] }}</h3>
                            @endif
                            <p>{{ $pillar['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ OUR STONES ═══════════════════════════ --}}
    @if($products->count())
        <section class="mt-section ab-stones">
            <div class="mt-container">
                <div class="ab-head ab-head-row" data-ab-reveal>
                    <div>
                        <span class="mt-eyebrow">{{ __('messages.ab_stones_kicker') }}</span>
                        <h2 class="mt-heading">{{ __('messages.ab_stones_title') }}</h2>
                        <p class="ab-sublead">{{ __('messages.ab_stones_lead') }}</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="mt-btn mt-btn-outline">{{ __('messages.all_products') }} {!! $arrow !!}</a>
                </div>

                <div class="ab-stones-grid">
                    @foreach($products as $i => $product)
                        @php $category = $product->categories->first(); @endphp
                        <a class="ab-stone {{ $i === 0 ? 'is-lead' : '' }}" data-ab-reveal style="--ab-delay:{{ min($i, 5) * 70 }}ms"
                           href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">
                            <img src="{{ $i === 0 ? $product->main_image_url : $product->medium_image_url }}"
                                 alt="{{ $product->getTranslation('name', $locale) }}" loading="lazy" decoding="async">
                            <span class="ab-stone-cap">
                                <strong>{{ $product->getTranslation('name', $locale) }}</strong>
                                @if($category)
                                    <small>{{ $category->getTranslation('name', $locale) }}</small>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ IN THE FIELD (exhibition) ═══════════════════════════ --}}
    @if($event && $eventPhotos->count() >= 3)
        @php $eventUrl = route('events.show', $event->getTranslation('slug', $locale)); @endphp
        <section class="ab-field">
            <div class="mt-container ab-field-grid">
                <div class="ab-field-copy" data-ab-reveal>
                    <span class="mt-eyebrow">{{ __('messages.ab_field_kicker') }}</span>
                    <h2 class="mt-heading">{{ __('messages.ab_field_title') }}</h2>
                    <p>{{ __('messages.ab_field_lead', ['event' => $event->getTranslation('title', $locale)]) }}</p>
                    <a href="{{ $eventUrl }}" class="mt-btn mt-btn-primary">{{ __('messages.exh_view_gallery') }} {!! $arrow !!}</a>
                </div>
                <div class="ab-mosaic" data-ab-reveal>
                    @foreach($eventPhotos as $i => $shot)
                        @php $caption = $event->galleryCaption($shot, $locale); @endphp
                        <a class="ab-mos ab-mos-{{ $i + 1 }}" href="{{ $eventUrl }}">
                            <img src="{{ $shot->hasGeneratedConversion('thumb') ? $shot->getUrl('thumb') : $shot->getUrl() }}"
                                 alt="{{ $caption }}" loading="lazy" decoding="async">
                            @if($caption !== '')
                                <span>{{ $caption }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ WHERE TO FIND US ═══════════════════════════ --}}
    @if(count($offices) || $contact['phone'] || $contact['email'])
        <section class="mt-section ab-where">
            <div class="mt-container">
                <div class="ab-head" data-ab-reveal>
                    <span class="mt-eyebrow">{{ __('messages.contact_info') }}</span>
                    <h2 class="mt-heading">{{ __('messages.ab_where_title') }}</h2>
                </div>
                <div class="ab-where-grid">
                    @foreach($offices as $i => $office)
                        <article class="ab-office" data-ab-reveal style="--ab-delay:{{ $i * 90 }}ms">
                            <span class="ab-office-ico" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            @if($office['label'])
                                <h3>{{ $office['label'] }}</h3>
                            @endif
                            <p>{{ $office['text'] }}</p>
                        </article>
                    @endforeach

                    <article class="ab-office ab-office-info" data-ab-reveal style="--ab-delay:{{ count($offices) * 90 }}ms">
                        <ul>
                            @if($contact['hours'])
                                <li><span>{{ __('messages.ab_hours') }}</span><strong><bdi dir="ltr">{{ $contact['hours'] }}</bdi></strong></li>
                            @endif
                            @if($contact['phone'])
                                <li><span>{{ __('messages.ab_call') }}</span><a href="tel:{{ preg_replace('/[^\d+]/', '', $contact['phone']) }}" dir="ltr">{{ $contact['phone'] }}</a></li>
                            @endif
                            @if($contact['email'])
                                <li><span>{{ __('messages.ab_email_us') }}</span><a href="mailto:{{ $contact['email'] }}" dir="ltr">{{ $contact['email'] }}</a></li>
                            @endif
                        </ul>
                        @if($contact['map'])
                            <a href="{{ $contact['map'] }}" target="_blank" rel="noopener noreferrer" class="ab-maplink">{{ __('messages.ab_open_map') }} {!! $arrow !!}</a>
                        @endif
                    </article>
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════════════ CTA ═══════════════════════════ --}}
    <section class="mt-section--tight ab-cta-wrap">
        <div class="mt-container">
            <div class="ab-cta" data-ab-reveal>
                <span class="ab-stripes ab-stripes-cta" aria-hidden="true"></span>
                <h2>{{ __('messages.ab_cta_title') }}</h2>
                <p>{{ __('messages.ab_cta_text') }}</p>
                <div class="ab-actions">
                    <a href="{{ route('contact') }}" class="mt-btn ab-btn-white">{{ __('messages.contact') }}</a>
                    @if($contact['phone'])
                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $contact['phone']) }}" class="mt-btn mt-btn-ghost-white" dir="ltr">{{ $contact['phone'] }}</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
