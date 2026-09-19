{{-- Owner / founder card. Vars: $founder (a CMS page using the "profile" template), $locale. --}}
@php
    $fName    = $founder->getTranslation('title', $locale);
    $fRole    = $founder->getTranslation('excerpt', $locale);
    $fBio     = $founder->getTranslation('content', $locale);
    $portrait = $founder->getFirstMediaUrl('cover');
    $phone    = display_phone(\App\Models\Setting::get('site_phone'));
    // Monogram from the Latin spelling of the name, whatever the visitor's language is
    $initials = collect(preg_split('/\s+/u', trim($founder->getTranslation('title', 'en', true) ?: $fName)))
        ->filter()->take(2)->map(fn ($w) => \Illuminate\Support\Str::upper(mb_substr($w, 0, 1)))->implode('');
@endphp
<article class="ab-founder-card" data-ab-reveal>
    <div class="ab-portrait">
        @if($portrait)
            <img src="{{ $portrait }}" alt="{{ $fName }}" loading="lazy" decoding="async">
        @else
            <span class="ab-monogram" aria-hidden="true">{{ $initials }}</span>
        @endif
    </div>
    <div class="ab-founder-copy">
        <span class="mt-eyebrow">{{ __('messages.ab_founder_kicker') }}</span>
        <h2 class="ab-founder-name">{{ $fName }}</h2>
        @if($fRole)
            <p class="ab-role">{{ $fRole }}</p>
        @endif
        <div class="mt-prose ab-prose">{!! $fBio !!}</div>
        <div class="ab-chips">
            @if($phone)
                <a class="ab-chip" href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" dir="ltr">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    {{ $phone }}
                </a>
            @endif
            <a class="ab-chip is-solid" href="{{ route('contact') }}">{{ __('messages.contact') }}</a>
        </div>
    </div>
</article>
