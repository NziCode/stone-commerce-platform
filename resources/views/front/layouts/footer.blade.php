@php
    $footerMenu = \App\Models\Menu::getByLocation('footer');
    $siteName   = \App\Models\Setting::get('site_name', config('app.name'));
    $siteLogo   = \App\Models\Setting::get('site_logo');
    $siteTagline= \App\Models\Setting::get('site_tagline');
    $sitePhone  = \App\Models\Setting::get('site_phone');
    $siteEmail  = \App\Models\Setting::get('site_email');
    $siteAddress= \App\Models\Setting::get('site_address');
    $social = [
        'facebook'  => \App\Models\Setting::get('social_facebook'),
        'twitter'   => \App\Models\Setting::get('social_twitter'),
        'instagram' => \App\Models\Setting::get('social_instagram'),
        'linkedin'  => \App\Models\Setting::get('social_linkedin'),
        'youtube'   => \App\Models\Setting::get('social_youtube'),
    ];
    $socialIcons = [
        'facebook'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.33v6.99A10 10 0 0 0 22 12z"/></svg>',
        'twitter'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.92a8.2 8.2 0 0 1-2.36.65 4.1 4.1 0 0 0 1.8-2.27 8.22 8.22 0 0 1-2.6 1 4.1 4.1 0 0 0-7 3.74A11.65 11.65 0 0 1 3.39 4.6a4.1 4.1 0 0 0 1.27 5.47 4.07 4.07 0 0 1-1.86-.51v.05a4.1 4.1 0 0 0 3.29 4.02 4.1 4.1 0 0 1-1.85.07 4.11 4.11 0 0 0 3.83 2.85A8.23 8.23 0 0 1 2 18.57a11.6 11.6 0 0 0 6.29 1.85c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.36-.01-.53A8.3 8.3 0 0 0 22 5.92z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>',
        'linkedin'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 5a2 2 0 1 1 0 4 2 2 0 0 1 0-4zM3.5 8.98h6.88V21H3.5zM14.5 8.98h6.6v1.68h.1c.92-1.68 3.16-1.68 4.06 0 1 1.83.8 4.16.8 6.06V21h-6.88v-5.5c0-1.3-.02-3-1.84-3s-2.12 1.4-2.12 2.9V21h-6.7z"/></svg>',
        'youtube'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 12s0-3.6-.46-5.32a3 3 0 0 0-2.12-2.12C18.7 4.1 12 4.1 12 4.1s-6.7 0-8.42.46A3 3 0 0 0 1.46 6.68C1 8.4 1 12 1 12s0 3.6.46 5.32a3 3 0 0 0 2.12 2.12C5.3 19.9 12 19.9 12 19.9s6.7 0 8.42-.46a3 3 0 0 0 2.12-2.12C23 15.6 23 12 23 12z"/><path d="m9.75 15.02 5.75-3.02-5.75-3.02z" fill="currentColor" stroke="none"/></svg>',
    ];
@endphp

{{-- ═══ Newsletter band ═══ --}}
<div class="mt-newsletter">
    <div class="mt-container">
        <div>
            <h3>{{ __('messages.newsletter_title') }}</h3>
            <p>{{ __('messages.newsletter_desc') ?? '' }}</p>
        </div>
        <form class="mt-newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="{{ __('messages.newsletter_placeholder') }}" required>
            <button type="submit">{{ __('messages.newsletter_subscribe') }}</button>
        </form>
    </div>
</div>

{{-- ═══ Footer main ═══ --}}
<footer class="mt-footer mt-body">
    <div class="mt-footer-top">
        <div class="mt-container mt-footer-grid">

            {{-- About / brand --}}
            <div>
                <a href="{{ route('home') }}" class="mt-logo" style="margin-bottom:1.1rem;display:inline-flex">
                    @if($siteLogo)
                        <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}">
                    @else
                        <span class="mt-logo-text">{{ $siteName }}</span>
                    @endif
                </a>
                <p>{{ $siteTagline ?: __('messages.footer_about_fallback') ?? '' }}</p>
                <div class="mt-social">
                    @foreach($socialIcons as $platform => $icon)
                        @if($social[$platform])
                            <a href="{{ $social[$platform] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $platform }}">
                                {!! $icon !!}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Footer menu / quick links --}}
            @if($footerMenu && $footerMenu->items->count())
                @foreach($footerMenu->items->take(2) as $section)
                    <div>
                        <h4>{{ $section->getTranslation('label', app()->getLocale()) }}</h4>
                        <ul>
                            @foreach($section->children as $child)
                                <li><a href="{{ $child->href }}">{{ $child->getTranslation('label', app()->getLocale()) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @else
                <div>
                    <h4>{{ __('messages.quick_links') }}</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                        <li><a href="{{ route('posts.index') }}">{{ __('messages.news') }}</a></li>
                        <li><a href="{{ route('events.index') }}">{{ __('messages.events') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ __('messages.contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4>{{ __('messages.my_account') }}</h4>
                    <ul>
                        @auth
                            <li><a href="{{ route('profile.index') }}">{{ __('messages.profile') }}</a></li>
                            <li><a href="{{ route('orders.index') }}">{{ __('messages.orders') }}</a></li>
                            <li><a href="{{ route('wishlist.index') }}">{{ __('messages.wishlist') }}</a></li>
                            <li><a href="{{ route('cart.index') }}">{{ __('messages.cart') }}</a></li>
                        @else
                            <li><a href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                            <li><a href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                        @endauth
                    </ul>
                </div>
            @endif

            {{-- Contact --}}
            <div>
                <h4>{{ __('messages.contact_info') }}</h4>
                <ul class="mt-footer-contact">
                    @if($siteAddress)
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $siteAddress }}</span>
                        </li>
                    @endif
                    @if($sitePhone)
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span><a href="tel:{{ $sitePhone }}">{{ $sitePhone }}</a></span>
                        </li>
                    @endif
                    @if($siteEmail)
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
                            <span><a href="mailto:{{ $siteEmail }}">{{ $siteEmail }}</a></span>
                        </li>
                    @endif
                </ul>
            </div>

        </div>
    </div>

    <div class="mt-footer-bottom">
        <div class="mt-container">
            <span>© {{ date('Y') }} {{ $siteName }} — {{ __('messages.all_rights_reserved') }}</span>
            <span>
                <a href="{{ route('contact') }}">{{ __('messages.contact') }}</a>
            </span>
        </div>
    </div>
</footer>
