@php
    $menu = \App\Models\Menu::getByLocation('header');
    $cartCount = auth()->check()
        ? (\App\Models\Cart::where('user_id', auth()->id())->first()?->items_count ?? 0)
        : 0;
    $wishCount = auth()->check() && method_exists(auth()->user(), 'wishlists')
        ? auth()->user()->wishlists()->count()
        : 0;
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $siteLogo = \App\Models\Setting::get('site_logo');
    $sitePhone = display_phone(\App\Models\Setting::get('site_phone'));
    $siteEmail = \App\Models\Setting::get('site_email');
    $siteWorkingHours = \App\Models\Setting::get('site_working_hours');
    $languages = \App\Models\Language::allActive();
    $isRtl = in_array(app()->getLocale(), ['fa', 'ar']);
@endphp

<header class="main-header_area position-relative mt-body">

    {{-- ═══ Top utility bar ═══ --}}
    <div class="mt-topbar d-none d-lg-block">
        <div class="mt-container">
            <div class="mt-topbar-left">
                @if($sitePhone)
                    <a href="tel:{{ $sitePhone }}" class="mt-topbar-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        {{ $sitePhone }}
                    </a>
                @endif
                @if($siteEmail)
                    <a href="mailto:{{ $siteEmail }}" class="mt-topbar-item mt-hide-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z" opacity="0"/><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                        {{ $siteEmail }}
                    </a>
                @endif
                @if($siteWorkingHours)
                    <span class="mt-topbar-item mt-hide-sm">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        {{ $siteWorkingHours }}
                    </span>
                @endif
            </div>
            <div class="mt-topbar-right">
                @auth
                    <a href="{{ route('profile.index') }}">{{ __('messages.profile') }}: {{ auth()->user()->name }}</a>
                @else
                    <a href="{{ route('login') }}">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}">{{ __('messages.register') }}</a>
                @endauth
                <span style="opacity:.3">|</span>
                <a href="{{ route('contact') }}">{{ __('messages.contact') ?? 'Contact' }}</a>
            </div>
        </div>
    </div>

    {{-- ═══ Main header ═══ --}}
    <div class="mt-header header-sticky" data-bg-color="#00225a">
        <div class="mt-container">

            <a href="{{ route('home') }}" class="mt-logo">
                @if($siteLogo)
                    <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}">
                @else
                    <span class="mt-logo-text">{{ $siteName }}<small>{{ __('messages.welcome') }}</small></span>
                @endif
            </a>

            <nav class="mt-nav">
                <ul>
                    @if($menu)
                        @foreach($menu->items as $item)
                            <li class="{{ $item->children->count() ? 'drop-holder' : '' }}">
                                <a href="{{ $item->href }}" target="{{ $item->target }}">
                                    <span>{{ $item->getTranslation('label', app()->getLocale()) }}</span>
                                </a>
                                @if($item->children->count())
                                    <ul class="drop-menu">
                                        @foreach($item->children as $child)
                                            <li>
                                                <a href="{{ $child->href }}" target="{{ $child->target }}">
                                                    {{ $child->getTranslation('label', app()->getLocale()) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </nav>

            <div class="mt-header-actions">
                {{-- Search --}}
                <div class="mt-lang">
                    <a href="javascript:void(0)" class="mt-icon-btn toolbar-btn" id="searchToggleBtn" onclick="document.getElementById('mtSearchBox').classList.toggle('open')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </a>
                    <div id="mtSearchBox" class="mt-lang-menu" style="min-width:280px">
                        <form class="search-form" action="{{ route('search') }}" method="GET" style="display:flex;gap:6px;padding:4px">
                            <input class="input-field" type="search" name="q" value="{{ request('q') }}"
                                   placeholder="{{ __('messages.search') }}"
                                   style="flex:1;border:1px solid var(--stone-200);border-radius:10px;padding:.55rem .7rem;font-family:inherit">
                            <button type="submit" class="mt-btn mt-btn-primary mt-btn-sm" style="padding:.55rem .8rem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Language --}}
                <div class="mt-lang">
                    <button type="button" class="mt-lang-btn" id="langBtn" onclick="toggleLangDropdown(event)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20z"/></svg>
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <div id="langDropdown" class="mt-lang-menu">
                        @foreach($languages as $lang)
                            <a href="{{ route('set.locale', $lang->code) }}" class="{{ app()->getLocale() === $lang->code ? 'active' : '' }}">
                                <span>{{ $lang->flag }}</span><span>{{ $lang->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Wishlist --}}
                @auth
                    <a href="{{ route('wishlist.index') }}" class="mt-icon-btn d-none d-md-inline-flex" title="{{ __('messages.wishlist') ?? 'Wishlist' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        @if($wishCount) <span class="mt-badge">{{ $wishCount }}</span> @endif
                    </a>
                @endauth

                {{-- Cart --}}
                <a href="#miniCart" class="mt-icon-btn minicart-btn toolbar-btn" title="{{ __('messages.cart') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <span class="mt-badge">{{ $cartCount }}</span>
                </a>

                {{-- Burger --}}
                <a href="#mobileMenu" class="mt-icon-btn mt-burger mobile-menu_btn toolbar-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ Mobile Menu (offcanvas) ═══ --}}
    <div class="mobile-menu_wrapper" id="mobileMenu">
        <div class="offcanvas-body" style="background:var(--ink)">
            <div class="inner-body">
                <div class="offcanvas-top" style="display:flex;justify-content:space-between;align-items:center;padding:1.2rem">
                    <span class="mt-logo-text" style="color:#fff">{{ $siteName }}</span>
                    <a href="#" class="button-close" style="color:#fff;font-size:1.6rem;line-height:1">&times;</a>
                </div>
                <div class="offcanvas-menu_area">
                    <nav class="offcanvas-navigation">
                        <ul class="mobile-menu">
                            @if($menu)
                                @foreach($menu->items as $item)
                                    <li class="{{ $item->children->count() ? 'menu-item-has-children' : '' }}">
                                        <a href="{{ $item->href }}">
                                            <span class="mm-text">
                                                {{ $item->getTranslation('label', app()->getLocale()) }}
                                                @if($item->children->count())
                                                    <i class="ion-ios-arrow-down"></i>
                                                @endif
                                            </span>
                                        </a>
                                        @if($item->children->count())
                                            <ul class="sub-menu">
                                                @foreach($item->children as $child)
                                                    <li>
                                                        <a href="{{ $child->href }}">
                                                            <span class="mm-text">{{ $child->getTranslation('label', app()->getLocale()) }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            @endif

                            <li class="menu-item-has-children">
                                <a href="#"><span class="mm-text">Language / زبان <i class="ion-ios-arrow-down"></i></span></a>
                                <ul class="sub-menu">
                                    @foreach($languages as $lang)
                                        <li>
                                            <a href="{{ route('set.locale', $lang->code) }}">
                                                <span class="mm-text" style="font-family:Arial,sans-serif">{{ $lang->flag }} {{ $lang->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>

                            @auth
                                <li><a href="{{ route('profile.index') }}"><span class="mm-text">{{ __('messages.profile') }}</span></a></li>
                                <li><a href="{{ route('orders.index') }}"><span class="mm-text">{{ __('messages.orders') }}</span></a></li>
                                <li><a href="{{ route('wishlist.index') }}"><span class="mm-text">{{ __('messages.wishlist') ?? 'Wishlist' }}</span></a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" style="background:none;border:none;padding:0;width:100%;text-align:start">
                                            <span class="mm-text">{{ __('messages.logout') }}</span>
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li><a href="{{ route('login') }}"><span class="mm-text">{{ __('messages.login') }}</span></a></li>
                                <li><a href="{{ route('register') }}"><span class="mm-text">{{ __('messages.register') }}</span></a></li>
                            @endauth
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ Mini Cart (offcanvas) ═══ --}}
    <div class="offcanvas-minicart_wrapper" id="miniCart">
        <div class="offcanvas-body">
            <div class="minicart-content">
                <div class="minicart-heading">
                    <h4 class="mb-0">{{ __('messages.cart') }}</h4>
                    <a href="#" class="button-close"><i class="ion-ios-close-empty"></i></a>
                </div>
                @auth
                    @php
                        $miniCart = \App\Models\Cart::where('user_id', auth()->id())
                            ->with('items.product.media')
                            ->first();
                    @endphp
                    @if($miniCart && !$miniCart->isEmpty)
                        <ul class="minicart-list">
                            @foreach($miniCart->items as $item)
                                <li class="minicart-product">
                                    <form action="{{ route('cart.remove', $item->product) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="product-item_remove" style="background:none;border:none">
                                            <i class="ion-ios-close-empty"></i>
                                        </button>
                                    </form>
                                    <div class="product-item_img">
                                        <img class="img-full" src="{{ $item->product->thumb_url }}"
                                             alt="{{ $item->product->getTranslation('name', app()->getLocale()) }}">
                                    </div>
                                    <div class="product-item_content">
                                        <a class="product-item_title"
                                           href="{{ route('products.show', $item->product->getTranslation('slug', app()->getLocale())) }}">
                                            {{ $item->product->getTranslation('name', app()->getLocale()) }}
                                        </a>
                                        <span class="product-item_quantity">
                                            {{ number_format($item->price) }} {{ $item->currency }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="minicart-item_total">
                            <span>{{ __('messages.cart') }}</span>
                            <span class="ammount">{{ number_format($miniCart->total) }}</span>
                        </div>
                        <div class="group-btn_wrap d-grid gap-2">
                            <a href="{{ route('cart.index') }}" class="mt-btn mt-btn-outline">{{ __('messages.cart') }}</a>
                            <a href="{{ route('checkout.index') }}" class="mt-btn mt-btn-primary">{{ __('messages.checkout') ?? 'Checkout' }}</a>
                        </div>
                    @else
                        <p class="text-center py-4">{{ __('messages.empty_cart') ?? 'Your cart is empty' }}</p>
                        <div class="group-btn_wrap d-grid">
                            <a href="{{ route('products.index') }}" class="mt-btn mt-btn-primary">{{ __('messages.products') }}</a>
                        </div>
                    @endif
                @else
                    <p class="text-center py-4">{{ __('messages.login') }}</p>
                    <div class="group-btn_wrap d-grid">
                        <a href="{{ route('login') }}" class="mt-btn mt-btn-primary">{{ __('messages.login') }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="global-overlay"></div>
</header>

<script>
    function toggleLangDropdown(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('mtSearchBox')?.classList.remove('open');
        var dropdown = document.getElementById('langDropdown');
        dropdown.classList.toggle('open');
    }
    document.addEventListener('click', function (e) {
        var dropdown = document.getElementById('langDropdown');
        var btn = document.getElementById('langBtn');
        if (dropdown && btn && !btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
        var search = document.getElementById('mtSearchBox');
        var sbtn = document.getElementById('searchToggleBtn');
        if (search && sbtn && !sbtn.contains(e.target) && !search.contains(e.target)) {
            search.classList.remove('open');
        }
    });
</script>
