@php
    $footerMenu = \App\Models\Menu::getByLocation('footer');
    $siteName   = \App\Models\Setting::get('site_name', config('app.name'));
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
@endphp

{{-- ═══ Newsletter ═══ --}}
<div class="newsletter-area pt-9 pb-8"
     data-bg-image="{{ asset('assets/images/newsletter/bg/1-1-1920x198.png') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="newsletter-item text-white">
                    <div class="newsletter-content">
                        <h2 class="title text-lg-end text-center mb-0">
                            {{ __('messages.newsletter_title') }}
                        </h2>
                    </div>
                    <div class="newsletter-form_wrap align-self-center">
                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="form-field">
                                <input class="input-field" type="email" name="email"
                                       placeholder="{{ __('messages.newsletter_placeholder') }}">
                            </div>
                            <div class="form-btn_wrap">
                                <button class="btn btn-secondary btn-secondary-hover btn-lg rounded-0" type="submit">
                                    {{ __('messages.newsletter_subscribe') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ Footer Main ═══ --}}
<div class="footer-area">
    <div class="footer-top pt-100 pb-80"
         data-bg-image="{{ asset('assets/images/footer/bg/1-1-1920x454.png') }}">
        <div class="container">
            <div class="row">

                {{-- درباره سایت --}}
                <div class="col-xl-3 col-lg-3">
                    <div class="widget-item text-hawkes-blue">
                        <div class="footer-logo pb-5">
                            <a href="{{ route('home') }}">
                                @if(\App\Models\Setting::get('site_logo'))
                                    <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="{{ $siteName }}">
                                @else
                                    <img src="{{ asset('assets/images/logo/white.svg') }}" alt="{{ $siteName }}">
                                @endif
                            </a>
                        </div>
                        <p class="short-desc font-size-16 mb-5">
                            {{ \App\Models\Setting::get('site_tagline') }}
                        </p>
                        @if($sitePhone)
                            <div class="inquary">
                                <h5 class="text-primary">{{ __('messages.contact') }}</h5>
                                <a href="tel:{{ $sitePhone }}">{{ $sitePhone }}</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- منوی فوتر از دیتابیس --}}
                @if($footerMenu)
                    @foreach($footerMenu->items->take(2) as $section)
                        <div class="col-xl-3 col-lg-2 col-sm-6 pl-xl-80 pt-8 pt-lg-0">
                            <div class="widget-item">
                                <h3 class="heading text-white mb-6">
                                    {{ $section->getTranslation('label', app()->getLocale()) }}
                                </h3>
                                <ul class="widget-list-item text-hawkes-blue">
                                    @foreach($section->children as $child)
                                        <li>
                                            <a href="{{ $child->href }}">
                                                {{ $child->getTranslation('label', app()->getLocale()) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- لینک‌های سریع (fallback) --}}
                    <div class="col-xl-3 col-lg-2 col-sm-6 pl-xl-80 pt-8 pt-lg-0">
                        <div class="widget-item">
                            <h3 class="heading text-white mb-6">{{ __('messages.quick_links') }}</h3>
                            <ul class="widget-list-item text-hawkes-blue">
                                <li><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                                <li><a href="{{ route('products.index') }}">{{ __('messages.products') }}</a></li>
                                <li><a href="{{ route('posts.index') }}">{{ __('messages.news') }}</a></li>
                                <li><a href="{{ route('events.index') }}">{{ __('messages.events') }}</a></li>
                                <li><a href="{{ route('contact') }}">{{ __('messages.contact') }}</a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- حساب کاربری (fallback) --}}
                    <div class="col-xl-3 col-lg-3 col-sm-6 ps-lg-10 pt-8 pt-lg-0">
                        <div class="widget-item">
                            <h3 class="heading text-white mb-6">{{ __('messages.my_account') }}</h3>
                            <ul class="widget-list-item text-hawkes-blue">
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
                    </div>
                @endif

                {{-- اطلاعات تماس --}}
                <div class="col-xl-3 col-lg-4 pt-8 pt-lg-0">
                    <div class="widget-item">
                        <h3 class="heading text-white mb-6">{{ __('messages.contact_info') }}</h3>
                        <div class="widget-list-item text-hawkes-blue">
                            @if($siteAddress)
                                <div class="widget-address pb-5">
                                    <p class="mb-1">{{ $siteAddress }}</p>
                                    @if($sitePhone)
                                        <span>
                                            <a href="tel:{{ $sitePhone }}" class="text-hawkes-blue">
                                                {{ $sitePhone }}
                                            </a>
                                        </span>
                                    @endif
                                </div>
                            @endif
                            @if($siteEmail)
                                <div class="widget-address">
                                    <p class="mb-1">
                                        <a href="mailto:{{ $siteEmail }}" class="text-hawkes-blue">
                                            {{ $siteEmail }}
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══ Footer Bottom ═══ --}}
    <div class="footer-bottom py-3 text-hawkes-blue" data-bg-color="#00225a">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-4">
                    <ul class="social-link">
                        @foreach(['facebook' => 'fa-facebook', 'twitter' => 'fa-twitter', 'instagram' => 'fa-instagram', 'linkedin' => 'fa-linkedin', 'youtube' => 'fa-youtube'] as $platform => $icon)
                            @if($social[$platform])
                                <li class="{{ $platform }}">
                                    <a href="{{ $social[$platform] }}" target="_blank" rel="noopener noreferrer">
                                        <i class="fa {{ $icon }}"></i>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6 col-sm-8 align-self-center">
                    <div class="copyright">
                        <span class="copyright-text">
                            © {{ date('Y') }} {{ $siteName }} — {{ __('messages.all_rights_reserved') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
