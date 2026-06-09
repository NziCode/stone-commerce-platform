@php
    $menu = \App\Models\Menu::getByLocation('header');
    $cartCount = auth()->check()
        ? (\App\Models\Cart::where('user_id', auth()->id())->first()?->items_count ?? 0)
        : 0;
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $siteLogo = \App\Models\Setting::get('site_logo');
    $sitePhone = \App\Models\Setting::get('site_phone');
    $siteWorkingHours = \App\Models\Setting::get('site_working_hours');
    $languages = \App\Models\Language::allActive();
    $isRtl = in_array(app()->getLocale(), ['fa', 'ar']);
@endphp

<header class="main-header_area position-relative">

    {{-- Header Top --}}
    <div class="header-top py-6 py-lg-3" data-bg-color="#ff5e13">
        <div class="container">
            <div class="row align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">

                {{-- اطلاعات تماس --}}
                <div class="{{ $isRtl ? 'offset-xl-2 col-xl-4 col-lg-5' : 'offset-xl-2 offset-lg-3 col-xl-4 col-lg-5' }} d-none d-lg-block">
                    <div class="header-top-left ml-8 {{ $isRtl ? 'flex-row-reverse' : ''}}">
                        @if($sitePhone)
                            <div class="contact-number">
                                <img src="{{ asset('assets/images/header/icon/phone.png') }}" alt="Phone">
                                <a href="tel:{{ $sitePhone }}">{{ $sitePhone }}</a>
                            </div>
                        @else
                            <div class="contact-number">
                                <img src="{{ asset('assets/images/header/icon/phone.png') }}" alt="Phone">
                                <a href="tel:02433467247">02433467247</a>
                            </div>
                        @endif
                        @if($siteWorkingHours)
                            <div class="time-schedule">
                                <img src="{{ asset('assets/images/header/icon/clock.png') }}" alt="Clock">
                                <span>{{ $siteWorkingHours }}</span>
                            </div>
                        @else
                            <div class="time-schedule">
                                <img src="{{ asset('assets/images/header/icon/clock.png') }}" alt="Clock">
                                <span>9.00 am - 11.00 pm</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- موبایل لوگو --}}
                <div class="col-sm-6 d-block d-lg-none">
                    <div class="header-logo d-flex">
                        <a href="{{ route('home') }}">
                            @if($siteLogo)
                                <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" style="max-height:45px">
                            @else
                                <img src="{{ asset('assets/images/logo/light.svg') }}" alt="{{ $siteName }}" style="max-height:45px">
                            @endif
                        </a>
                    </div>
                </div>

                {{-- سمت راست هدر --}}
                <div class="col-xl-6 col-lg-4 col-sm-6">
                    <div class="header-top-right">
                        <ul class="hassub-item"
                            style="display:flex;align-items:center;justify-content:flex-end;
                                   list-style:none;margin:0;padding:0;gap:20px;direction:{{ $isRtl ? 'rtl' : 'ltr' }}"">

                            @auth
                                <li class="login-info">
                                    <a href="{{ route('profile.index') }}">{{ auth()->user()->name }}</a>
                                </li>
                            @else
                                <li class="login-info">
                                    <a href="{{ route('login') }}">
                                        {{ __('messages.login') }}<span>/ {{ __('messages.register') }}</span>
                                    </a>
                                </li>
                            @endauth

                            <li class="minicart-wrap">
                                <a href="#miniCart" class="minicart-btn toolbar-btn">
                                    <div class="minicart-count">
                                        <img src="{{ asset('assets/images/header/icon/cart.png') }}" alt="Cart">
                                        <span class="quantity">{{ $cartCount }}</span>
                                    </div>
                                </a>
                            </li>

                            <li style="position:relative;list-style:none;padding-left: 0px !important;">
                                <a href="#"
                                   id="langBtn"
                                   onclick="toggleLangDropdown(event)"
                                   style="color:white;font-size:14px;font-weight:600;cursor:pointer;
                                          text-decoration:none;display:flex;align-items:center;gap:5px;
                                          font-family:Arial,sans-serif">
                                    <i class="fa fa-globe" style="font-size:15px"></i>
                                    <span style="font-family:Arial,sans-serif">{{ strtoupper(app()->getLocale()) }}</span>
                                    <i class="fa fa-caret-down" style="font-size:11px"></i>
                                </a>
                                <ul id="langDropdown"
                                    style="display:none;position:absolute;top:38px;right:0;
                                           min-width:150px;padding:6px 0;z-index:999999;
                                           list-style:none;margin:0;border-radius:8px;
                                           background:white;box-shadow:0 8px 25px rgba(0,0,0,0.15);
                                           border:1px solid #eee">
                                    @foreach($languages as $lang)
                                        <li style="padding:0;margin:0">
                                            <a href="{{ route('set.locale', $lang->code) }}"
                                               class="{{ app()->getLocale() === $lang->code ? 'active-lang' : '' }}">
                                                <span style="font-size:12px;line-height:1;flex-shrink:0">{{ $lang->flag }}</span>
                                                <span>{{ $lang->name }}</span>
                                                @if(app()->getLocale() === $lang->code)
                                                    <i class="fa fa-check" style="margin-right:auto;font-size:11px;color:#ff5e13"></i>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="mobile-menu_wrap d-block d-lg-none">
                                <a href="#mobileMenu" class="mobile-menu_btn toolbar-btn pl-0">
                                    <i class="fa fa-navicon"></i>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <div class="main-header header-sticky" data-bg-color="#00225a">
        <div class="container">
            <div class="main-header_nav">
                <div class="row align-items-center">
                    <div class="offset-xl-1 col-xl-10 d-none d-lg-block">
                        <div class="main-menu">
                            <nav class="main-nav">
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

                                    <li class="hassub-item-wrap d-none d-lg-inline-flex">
                                        <ul class="hassub-item">
                                            <li class="search-wrap hassub">
                                                <a href="#" class="search-btn">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                                <ul class="hassub-body search-body">
                                                    <li>
                                                        <form class="search-form" action="{{ route('search') }}" method="GET">
                                                            <div class="form-field">
                                                                <input class="input-field" type="search" name="q"
                                                                       value="{{ request('q') }}"
                                                                       placeholder="{{ __('messages.search') }}">
                                                            </div>
                                                            <div class="form-btn_wrap">
                                                                <button class="btn btn-secondary btn-primary-hover rounded-0">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Logo --}}
            <div class="header-logo-wrap d-none d-lg-flex">
                <div class="header-fixed-logo">
                    <a href="{{ route('home') }}">
                        @if($siteLogo)
                            <img src="{{ asset($siteLogo) }}" alt="{{ $siteName }}" style="max-height:55px">
                        @else
                            <img src="{{ asset('assets/images/logo/dark.svg') }}" alt="{{ $siteName }}" style="max-height:55px">
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div class="mobile-menu_wrapper" id="mobileMenu">
        <div class="offcanvas-body">
            <div class="inner-body">
                <div class="offcanvas-top">
                    <a href="#" class="button-close"><i class="ion-ios-close-empty"></i></a>
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
                                <a href="#">
                                    <span class="mm-text">Language / زبان <i class="ion-ios-arrow-down"></i></span>
                                </a>
                                <ul class="sub-menu">
                                    @foreach($languages as $lang)
                                        <li>
                                            <a href="{{ route('set.locale', $lang->code) }}">
                                                <span class="mm-text" style="font-family:Arial,sans-serif">
                                                    {{ $lang->flag }} {{ $lang->name }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>

                            @auth
                                <li>
                                    <a href="{{ route('profile.index') }}">
                                        <span class="mm-text">{{ __('messages.profile') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('orders.index') }}">
                                        <span class="mm-text">{{ __('messages.orders') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" style="background:none;border:none;padding:0;width:100%;text-align:right">
                                            <span class="mm-text">{{ __('messages.logout') }}</span>
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('login') }}">
                                        <span class="mm-text">{{ __('messages.login') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('register') }}">
                                        <span class="mm-text">{{ __('messages.register') }}</span>
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Mini Cart --}}
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
                            <a href="{{ route('cart.index') }}" class="btn btn-secondary btn-primary-hover">مشاهده سبد</a>
                            <a href="{{ route('checkout.index') }}" class="btn btn-secondary btn-primary-hover">تسویه حساب</a>
                        </div>
                    @else
                        <p class="text-center py-4">سبد خرید خالی است</p>
                        <div class="group-btn_wrap d-grid">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-primary-hover">مشاهده محصولات</a>
                        </div>
                    @endif
                @else
                    <p class="text-center py-4">برای مشاهده سبد خرید وارد شوید</p>
                    <div class="group-btn_wrap d-grid">
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-primary-hover">ورود</a>
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
        var dropdown = document.getElementById('langDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(e) {
        var dropdown = document.getElementById('langDropdown');
        var btn = document.getElementById('langBtn');
        if (dropdown && btn && !btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>
