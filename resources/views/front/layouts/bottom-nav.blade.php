{{-- ═══ Mobile bottom navigation + search sheet (visible ≤767px only, see theme-modern.css) ═══
     Rendered on the server so the links follow the language prefix, the labels are translated,
     the active tab is right and the cart tab only exists while the cart is in use. --}}
@php
    $bnLocale    = app()->getLocale();
    $bnCartCount = auth()->check()
        ? (\App\Models\Cart::where('user_id', auth()->id())->first()?->items_count ?? 0)
        : 0;
    $bnShowCart  = cart_enabled() || $bnCartCount > 0;
    $bnCategories = \App\Models\Category::active()->roots()->ordered()->get();

    $bnTabs = [
        [
            'key'    => 'home',
            'href'   => route('home'),
            'label'  => __('messages.home'),
            'active' => request()->routeIs('home'),
            'icon'   => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
        ],
        [
            'key'    => 'products',
            'href'   => route('products.index'),
            'label'  => __('messages.products'),
            'active' => request()->routeIs('products.*', 'categories.*'),
            'icon'   => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>',
        ],
        [
            'key'    => 'search',
            'href'   => route('search'),
            'label'  => __('messages.search'),
            'active' => request()->routeIs('search'),
            'icon'   => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        ],
    ];

    if ($bnShowCart) {
        $bnTabs[] = [
            'key'    => 'cart',
            'href'   => route('cart.index'),
            'label'  => __('messages.cart'),
            'active' => request()->routeIs('cart.*', 'checkout.*', 'payment.*'),
            'icon'   => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
            'badge'  => $bnCartCount,
        ];
    }

    // logged in → the account page; a guest → the login page (never a dead end)
    $bnTabs[] = [
        'key'    => 'account',
        'href'   => auth()->check() ? route('profile.index') : route('login'),
        'label'  => auth()->check() ? __('messages.account') : __('messages.login'),
        'active' => request()->routeIs('profile.*', 'orders.*', 'wishlist.*', 'login', 'register', 'password.*'),
        'icon'   => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    ];
@endphp

<nav id="mt-bottom-nav" aria-label="Primary">
    @foreach($bnTabs as $tab)
        <a href="{{ $tab['href'] }}"
           class="mt-bnav-item{{ $tab['active'] ? ' active' : '' }}"
           data-tab="{{ $tab['key'] }}"
           @if($tab['active']) aria-current="page" @endif
           @if($tab['key'] === 'search') id="mtBnavSearch" aria-controls="mtSearchSheet" aria-expanded="false" @endif>
            <span class="mt-bnav-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $tab['icon'] !!}</svg>
                @if(!empty($tab['badge']))
                    <b class="mt-bnav-badge">{{ $tab['badge'] }}</b>
                @endif
            </span>
            <span class="mt-bnav-label">{{ $tab['label'] }}</span>
        </a>
    @endforeach
</nav>

{{-- Full-width search panel that drops from the top (so the on-screen keyboard never covers it) --}}
<div id="mtSearchSheet" class="mt-search-sheet" role="dialog" aria-modal="true" aria-label="{{ __('messages.search') }}">
    <div class="mt-search-sheet__backdrop" data-mt-search-close></div>
    <div class="mt-search-sheet__panel">
        <form action="{{ route('search') }}" method="GET" role="search" autocomplete="off">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search') }}"
                   enterkeyhint="search" autocapitalize="off" spellcheck="false" aria-label="{{ __('messages.search') }}">
            <button type="submit" class="mt-search-sheet__go" aria-label="{{ __('messages.search') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </button>
            <button type="button" class="mt-search-sheet__close" data-mt-search-close aria-label="{{ __('messages.cancel') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </form>

        @if($bnCategories->count())
            <p class="mt-search-sheet__hint">{{ __('messages.categories') }}</p>
            <div class="mt-search-sheet__chips">
                @foreach($bnCategories as $bnCat)
                    <a href="{{ route('categories.show', $bnCat->getSlugForLocale($bnLocale)) }}">{{ $bnCat->getTranslation('name', $bnLocale) }}</a>
                @endforeach
            </div>
        @endif
    </div>
</div>
