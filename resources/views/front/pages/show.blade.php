@extends('front.layouts.app')
@section('title', $page->getTranslation('title', app()->getLocale()) . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')
    @php
        $locale = app()->getLocale();
        $isAbout = $page->getTranslation('slug', $locale) === 'about';
    @endphp

    @include('front.components.breadcrumb', [
        'title' => $page->getTranslation('title', $locale),
        'desc'  => $page->getTranslation('excerpt', $locale),
    ])

    <div class="mt-section">
        <div class="mt-container">

            @if($page->template === 'full-width')
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        @if($page->hasMedia('cover'))
                            <div class="mt-page-cover"><img src="{{ $page->getFirstMediaUrl('cover', 'thumb') }}" alt="{{ $page->getTranslation('title', $locale) }}"></div>
                        @endif
                        <div class="mt-prose">{!! $page->getTranslation('content', $locale) !!}</div>
                    </div>
                </div>

            @elseif($page->template === 'sidebar')
                <div class="row">
                    <div class="col-lg-8 order-lg-1 order-2">
                        @if($page->hasMedia('cover'))
                            <div class="mt-page-cover"><img src="{{ $page->getFirstMediaUrl('cover', 'thumb') }}" alt="{{ $page->getTranslation('title', $locale) }}"></div>
                        @else
                            <div class="mt-page-cover"><span class="mt-post-img-fallback"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg></span></div>
                        @endif

                        @if($isAbout)
                            @php
                                $aboutYears = \App\Models\Setting::get('about_years', '25');
                            @endphp
                            <div class="mt-stats-card" style="margin-bottom:2.2rem;box-shadow:var(--shadow-sm);border:1px solid var(--stone-100)">
                                <div class="mt-stat"><strong>{{ $aboutYears }}+</strong><span>{{ __('messages.experience_years') }}</span></div>
                                <div class="mt-stat"><strong>{{ \App\Models\Category::count() }}+</strong><span>{{ __('messages.stone_categories') }}</span></div>
                                <div class="mt-stat"><strong>{{ \App\Models\Product::count() }}+</strong><span>{{ __('messages.products') }}</span></div>
                                <div class="mt-stat"><strong>50+</strong><span>{{ __('messages.countries') ?? 'Countries' }}</span></div>
                            </div>
                        @endif

                        <div class="mt-prose">{!! $page->getTranslation('content', $locale) !!}</div>

                        @if($isAbout)
                            <div class="mt-band mt-on-dark" style="margin-top:2.4rem;text-align:center">
                                <h3 class="mt-heading" style="color:#fff;margin-bottom:.6rem">{{ __('messages.any_questions') }}</h3>
                                <p style="color:rgba(255,255,255,.78);margin-bottom:1.4rem">{{ __('messages.find_your_stone') }}</p>
                                <a href="{{ route('contact') }}" class="mt-btn mt-btn-primary">{{ __('messages.contact') }}</a>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-4 order-lg-2 order-1 pt-10 pt-lg-0">
                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">{{ __('messages.contact_info') }}</h3>
                            <ul class="mt-footer-contact" style="list-style:none;margin:0;padding:0;display:grid;gap:.9rem">
                                @if(\App\Models\Setting::get('site_phone'))
                                    <li style="display:flex;align-items:flex-start;gap:.6rem">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" style="color:var(--brand);flex-shrink:0;margin-top:3px"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        <a href="tel:{{ display_phone(\App\Models\Setting::get('site_phone')) }}" style="color:var(--stone-700);text-decoration:none">{{ display_phone(\App\Models\Setting::get('site_phone')) }}</a>
                                    </li>
                                @endif
                                @if(\App\Models\Setting::get('site_email'))
                                    <li style="display:flex;align-items:flex-start;gap:.6rem">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" style="color:var(--brand);flex-shrink:0;margin-top:3px"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
                                        <a href="mailto:{{ \App\Models\Setting::get('site_email') }}" style="color:var(--stone-700);text-decoration:none">{{ \App\Models\Setting::get('site_email') }}</a>
                                    </li>
                                @endif
                                @if(\App\Models\Setting::get('site_address'))
                                    <li style="display:flex;align-items:flex-start;gap:.6rem">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" style="color:var(--brand);flex-shrink:0;margin-top:3px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span style="color:var(--stone-700)">{{ \App\Models\Setting::get('site_address') }}</span>
                                    </li>
                                @endif
                            </ul>
                            <a href="{{ route('contact') }}" class="mt-btn mt-btn-primary" style="width:100%;margin-top:1.4rem">{{ __('messages.contact') }}</a>
                        </div>
                    </div>
                </div>

            @else
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        @if($page->hasMedia('cover'))
                            <div class="mt-page-cover"><img src="{{ $page->getFirstMediaUrl('cover', 'thumb') }}" alt="{{ $page->getTranslation('title', $locale) }}"></div>
                        @endif
                        <div class="mt-prose">{!! $page->getTranslation('content', $locale) !!}</div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
