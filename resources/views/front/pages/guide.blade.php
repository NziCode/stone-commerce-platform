@extends('front.layouts.app')

@section('title', $page->getTranslation('title', $locale) . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/guide.css') }}?v={{ @filemtime(public_path('assets/css/guide.css')) ?: 1 }}">
@endpush

@section('content')
    @php
        $isFa    = $locale === 'fa';
        $num     = fn ($n) => $isFa ? \App\Support\Jalali::toPersianDigits($n) : $n;
        $title   = $page->getTranslation('title', $locale);
        $excerpt = $page->getTranslation('excerpt', $locale);
        $telHref = $contact['phone'] ? 'tel:' . preg_replace('/[^\d+]/', '', $contact['phone']) : null;
    @endphp

    {{-- ═══════════════ HEADER ═══════════════ --}}
    <section class="gd-hero" @if($hero) style="--gd-hero:url('{{ $hero }}')" @endif>
        <div class="gd-hero-bg" aria-hidden="true"></div>
        <div class="mt-container gd-hero-inner">
            <ul class="mt-crumbs gd-crumbs">
                <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                <li><span class="is-active">{{ $title }}</span></li>
            </ul>
            <h1 class="gd-title">{{ $title }}</h1>
            @if($excerpt)
                <p class="gd-lead">{{ $excerpt }}</p>
            @endif
        </div>
    </section>

    {{-- ═══════════════ STEPS + CONTACT PANEL ═══════════════ --}}
    <section class="mt-section gd-body">
        <div class="mt-container">
            <div class="gd-grid">

                <div class="gd-main">
                    @if($intro !== '')
                        <div class="mt-prose gd-intro">{!! $intro !!}</div>
                    @endif

                    @if(count($steps))
                        <ol class="gd-steps">
                            @foreach($steps as $i => $step)
                                <li class="gd-step" style="--i:{{ $i }}">
                                    <span class="gd-step-no" aria-hidden="true">{{ $num($i + 1) }}</span>
                                    <div class="gd-step-card">
                                        @if($step['title'])
                                            <h2>{{ $step['title'] }}</h2>
                                        @endif
                                        <div class="gd-step-body">{!! $step['body'] !!}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @endif

                    @if($outro !== '')
                        <div class="mt-prose gd-outro">{!! $outro !!}</div>
                    @endif
                </div>

                <aside class="gd-aside">
                    <div class="gd-talk">
                        <span class="gd-talk-ico" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </span>
                        <h3>{{ __('messages.gd_talk_title') }}</h3>
                        <p>{{ __('messages.gd_talk_text') }}</p>

                        @if($contact['whatsapp'])
                            <a class="gd-btn gd-btn-wa" href="{{ $contact['whatsapp'] }}" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20" aria-hidden="true"><path d="M17.47 14.38c-.28-.14-1.64-.81-1.9-.9-.25-.1-.44-.14-.62.14-.18.27-.71.9-.87 1.08-.16.18-.32.2-.6.07-.27-.14-1.16-.43-2.2-1.36-.82-.73-1.36-1.62-1.53-1.9-.16-.27-.02-.42.12-.56.13-.13.27-.32.41-.49.14-.16.18-.27.27-.46.09-.18.05-.34-.02-.48-.07-.14-.62-1.5-.86-2.05-.22-.54-.45-.46-.62-.47-.16 0-.34-.01-.53-.01-.18 0-.48.07-.73.34-.25.27-.96.94-.96 2.29 0 1.35.98 2.66 1.12 2.84.14.18 1.93 2.95 4.68 4.13.65.28 1.16.45 1.56.58.66.21 1.25.18 1.72.11.52-.08 1.64-.67 1.87-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.18-.53-.32z"/><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.37 5.07L2 22l5.07-1.33A9.96 9.96 0 0 0 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm0 18.2a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.01.79.8-2.93-.2-.31A8.2 8.2 0 1 1 12 20.2z" fill-rule="evenodd"/></svg>
                                {{ __('messages.gd_whatsapp') }}
                            </a>
                        @endif

                        @if($telHref)
                            <a class="gd-btn gd-btn-call" href="{{ $telHref }}" dir="ltr">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                {{ $contact['phone'] }}
                            </a>
                        @endif

                        <a class="gd-btn gd-btn-stones" href="{{ route('products.index') }}">{{ __('messages.ab_cta_products') }}</a>

                        @if($contact['hours'])
                            <small class="gd-hours">{{ __('messages.ab_hours') }}: <bdi dir="ltr">{{ $contact['hours'] }}</bdi></small>
                        @endif

                        <p class="gd-hint">{{ __('messages.gd_reserve_hint') }}</p>
                    </div>
                </aside>

            </div>
        </div>
    </section>
@endsection
