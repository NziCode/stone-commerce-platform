@extends('front.layouts.app')

@section('title', isset($category)
    ? $category->getTranslation('name', app()->getLocale()) . ' — ' . \App\Models\Setting::get('site_name')
    : __('messages.products') . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <style>
        /* ── Toolbar ──────────────────────────────── */
        .shop-toolbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:14px 20px;background:#f4f8ff;margin-bottom:30px;}
        .shop-toolbar .showing-count{font-size:14px;color:#666;}
        .shop-toolbar .showing-count strong{color:#00225a;}
        .shop-toolbar-right{display:flex;align-items:center;gap:12px;}
        .sort-select{border:1px solid #dee2e6;padding:8px 14px;font-size:14px;background:#fff;color:#333;cursor:pointer;min-width:180px;}
        .view-btns button{width:36px;height:36px;border:1px solid #dee2e6;background:#fff;color:#999;cursor:pointer;transition:all .2s;}
        .view-btns button.active,.view-btns button:hover{background:#00225a;color:#fff;border-color:#00225a;}

        /* ── Sidebar misc ─────────────────────────── */
        .active-filter-tag{display:inline-flex;align-items:center;gap:6px;background:#00225a;color:#fff;padding:4px 10px;font-size:12px;margin-bottom:6px;}
        .active-filter-tag a{color:rgba(255,255,255,0.8);text-decoration:none;font-size:14px;}
        .sidebar-cta{background:#00225a;padding:24px 20px;text-align:center;}
        .sidebar-cta i{font-size:30px;color:#ff5e13;display:block;margin-bottom:10px;}
        .sidebar-cta h4{color:#fff;font-size:15px;margin-bottom:8px;}
        .sidebar-cta a.phone-link{color:#ff5e13;font-size:17px;font-weight:700;display:block;margin-bottom:14px;text-decoration:none;}
        .category-tree{list-style:none;padding:0;margin:0;}
        .category-tree > li{border-bottom:1px solid #f0f0f0;}
        .category-tree > li:last-child{border-bottom:none;}
        .cat-row{display:flex;align-items:center;justify-content:space-between;}
        .cat-toggle{background:none;border:1px solid #dee2e6;width:24px;height:24px;font-size:16px;cursor:pointer;color:#666;flex-shrink:0;margin-inline-start:6px;transition:all .2s;display:flex;align-items:center;justify-content:center;order:-1;}
        .cat-toggle.open{background:#ff5e13;border-color:#ff5e13;color:#fff;}
        .cat-row > a{flex:1;display:flex;justify-content:space-between;align-items:center;padding:10px 0;font-size:15px;color:#444;text-decoration:none;transition:color .2s;}
        .cat-row > a:hover,.cat-row > a.active{color:#ff5e13;font-weight:600;}
        .cat-row > a span{background:#f0f0f0;color:#666;border-radius:20px;padding:1px 8px;font-size:12px;min-width:28px;text-align:center;font-weight:400;margin-inline-start:6px;flex-shrink:0;}
        .cat-row > a.active span{background:#ff5e13;color:#fff;}
        .cat-children{list-style:none;padding:0 0 6px 16px;margin:0;display:none;}
        .cat-children.show{display:block;}
        [dir="rtl"] .cat-children{padding:0 16px 6px 0;}
        .cat-children li a{display:flex;justify-content:space-between;align-items:center;padding:7px 0;font-size:13px;color:#555;text-decoration:none;transition:color .2s;}
        .cat-children li a:hover,.cat-children li a.active{color:#ff5e13;font-weight:600;}
        .cat-children li a span{background:#f0f0f0;color:#666;border-radius:20px;padding:1px 6px;font-size:11px;min-width:22px;text-align:center;margin-inline-start:6px;flex-shrink:0;}
        .cat-children li a.active span{background:#ff5e13;color:#fff;}
        .no-products-found{text-align:center;padding:60px 20px;color:#888;}
        .no-products-found i{font-size:48px;color:#dee2e6;display:block;margin-bottom:16px;}
        .no-products-found h4{color:#555;margin-bottom:8px;}
        [dir="rtl"] .shop-toolbar{flex-direction:row-reverse;}
        [dir="rtl"] .shop-toolbar-right{flex-direction:row-reverse;}
        .breadcrumb-area .breadcrumb-content .breadcrumb{background:none;padding:0;margin:10px 0 0;display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:0;list-style:none;direction:ltr;}
        .breadcrumb-area .breadcrumb-item a{color:rgba(255,255,255,0.7);}
        .breadcrumb-area .breadcrumb-item.active{color:#ff5e13;}
        .breadcrumb-area .breadcrumb-item + .breadcrumb-item::before{content:'/';color:rgba(255,255,255,0.4);padding:0 8px;}

        /* ══ GRID CARD ══════════════════════════════ */
        .sc{background:#fff;border-radius:14px;overflow:hidden;border:1px solid #eef0f4;box-shadow:0 2px 10px rgba(0,0,0,.05);transition:box-shadow .25s,transform .25s;height:100%;display:flex;flex-direction:column;}
        .sc:hover{transform:translateY(-4px);box-shadow:0 12px 30px rgba(0,0,0,.1);}
        .sc-img{position:relative;aspect-ratio:4/3;overflow:hidden;background:#f5f5f5;}
        .sc-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s;display:block;}
        .sc:hover .sc-img img{transform:scale(1.06);}
        .sc-badges{position:absolute;top:10px;inset-inline-start:10px;display:flex;flex-direction:column;gap:4px;z-index:2;}
        .sc-b{font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;}
        .sc-b-new{background:#00225a;color:#fff;}
        .sc-b-hot{background:#ff5e13;color:#fff;}
        .sc-b-sold{background:#888;color:#fff;}
        .sc-b-res{background:#e8a000;color:#fff;}
        .sc-body{padding:14px 16px 16px;display:flex;flex-direction:column;gap:7px;flex:1;}
        .sc-cat{font-size:10px;font-weight:700;color:#ff5e13;text-transform:uppercase;letter-spacing:.06em;}
        .sc-name{font-size:14px;font-weight:700;color:#00225a;line-height:1.4;margin:0;}
        .sc-name a{color:inherit;text-decoration:none;}
        .sc-name a:hover{color:#ff5e13;}
        .sc-dims{display:flex;gap:5px;flex-wrap:wrap;}
        .sc-dim{display:inline-flex;align-items:center;gap:3px;background:#f4f6fa;border:0.5px solid #e8eaf0;padding:3px 8px;border-radius:20px;font-size:11px;}
        .sc-dim-k{color:#888;}
        .sc-dim-v{color:#00225a;font-weight:700;}
        .sc-divider{height:0.5px;background:#eef0f4;margin:1px 0;}
        .sc-prices{display:flex;flex-direction:column;gap:2px;margin-top:auto;}
        .sc-price-rial{font-size:20px;font-weight:800;color:#00225a;line-height:1.1;}
        .sc-price-sub{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
        .sc-price-eur{font-size:12px;font-weight:600;color:#1a6b3c;}
        .sc-price-usd{font-size:11px;color:#999;}
        .sc-price-req{font-size:13px;color:#888;font-style:italic;}
        .sc-foot{display:flex;align-items:center;justify-content:space-between;margin-top:6px;gap:8px;}
        .sc-status{font-size:11px;font-weight:700;display:flex;align-items:center;gap:4px;}
        .sc-status::before{content:'';width:6px;height:6px;border-radius:50%;background:currentColor;opacity:.7;}
        .sc-status-av{color:#1a7a45;}
        .sc-status-un{color:#cc3333;}
        .sc-status-re{color:#c47c00;}
        .sc-status-so{color:#888;}
        .sc-actions{display:flex;gap:5px;}
        .sc-btn{font-size:11px;font-weight:700;padding:6px 11px;border-radius:8px;text-decoration:none;border:none;cursor:pointer;transition:opacity .15s;white-space:nowrap;}
        .sc-btn:hover{opacity:.85;}
        .sc-btn-primary{background:#ff5e13;color:#fff;}
        .sc-btn-ink{background:#f4f6fa;border:0.5px solid #dde0e8;color:#00225a;}

        /* ══ LIST CARD ══════════════════════════════ */
        .pl{background:#fff;border:1px solid #eef0f4;border-radius:14px;overflow:hidden;display:flex;box-shadow:0 2px 10px rgba(0,0,0,.05);transition:box-shadow .25s;}
        .pl:hover{box-shadow:0 6px 24px rgba(0,0,0,.09);}
        .pl-img{width:200px;flex-shrink:0;position:relative;overflow:hidden;}
        .pl-img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s;}
        .pl:hover .pl-img img{transform:scale(1.04);}
        .pl-body{flex:1;padding:16px 18px;display:flex;gap:14px;}
        .pl-info{flex:1;display:flex;flex-direction:column;gap:6px;}
        .pl-name{font-size:16px;font-weight:700;color:#00225a;line-height:1.4;margin:0;}
        .pl-name a{color:inherit;text-decoration:none;}
        .pl-name a:hover{color:#ff5e13;}
        .pl-desc{font-size:12px;color:#666;line-height:1.7;margin:0;}
        .pl-right{width:170px;flex-shrink:0;display:flex;flex-direction:column;justify-content:center;gap:10px;border-inline-start:1px solid #eef0f4;padding-inline-start:14px;}
        .pl-price-rial{font-size:22px;font-weight:800;color:#00225a;line-height:1.1;}
        .pl-price-sub{font-size:11px;color:#999;margin-top:1px;}
        .pl-btns{display:flex;flex-direction:column;gap:6px;}
        .pl-btn{font-size:12px;font-weight:700;padding:8px 10px;border-radius:8px;text-decoration:none;border:none;cursor:pointer;text-align:center;transition:opacity .15s;display:block;}
        .pl-btn:hover{opacity:.85;}
        .pl-btn-primary{background:#ff5e13;color:#fff;}
        .pl-btn-ghost{background:#f4f6fa;border:0.5px solid #dde0e8;color:#00225a;}

        .product-col-list{display:none;}

        [dir="rtl"] .pl{flex-direction:row-reverse;}
        [dir="rtl"] .pl-body{flex-direction:row-reverse;}
        @media(max-width:768px){
            .pl{flex-direction:column;}
            .pl-img{width:100%;height:200px;}
            .pl-body{flex-direction:column;}
            .pl-right{width:100%;border-inline-start:none;border-top:1px solid #eef0f4;padding-inline-start:0;padding-top:12px;}
        }
    </style>
@endpush

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.products'),
        'title'    => isset($category) ? $category->getTranslation('name', app()->getLocale()) : __('messages.all_products'),
        'desc'     => (isset($category) && $category->getTranslation('description', app()->getLocale())) ? Str::limit($category->getTranslation('description', app()->getLocale()), 120) : null,
        'crumbs'   => array_filter([
            ['label' => __('messages.products'), 'url' => isset($category) ? route('products.index') : null],
            isset($category) && $category->parent ? ['label' => $category->parent->getTranslation('name', app()->getLocale()), 'url' => route('categories.show', $category->parent->getTranslation('slug', app()->getLocale()))] : null,
            isset($category) ? ['label' => $category->getTranslation('name', app()->getLocale())] : null,
        ]),
    ])

    {{-- Shop area --}}
    <div class="product-area py-140">
        <div class="container">
            <div class="row">

                {{-- ── Sidebar ── --}}
                <div class="col-lg-3 order-lg-1 order-2 pt-10 pt-lg-0
                    @if(in_array(app()->getLocale(), ['fa','ar'])) ps-lg-6 @else pe-lg-9 @endif">
                    <div class="sidebar-area">

                        {{-- Active filter --}}
                        @if(request('search'))
                            <div class="mb-6">
                                <span class="active-filter-tag">
                                    {{ request('search') }}
                                    <a href="{{ request()->fullUrlWithoutQuery('search') }}">×</a>
                                </span>
                            </div>
                        @endif

                        {{-- Search --}}
                        <div class="sidebar-widget sidebar-searchbar sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">{{ __('messages.search') }}</h3>
                            <form class="sidebar-form" method="GET"
                                  action="{{ isset($category)
                                      ? route('categories.show', $category->getTranslation('slug', app()->getLocale()))
                                      : route('products.index') }}">
                                <input class="searchbox-input" type="text" name="search"
                                       value="{{ request('search') }}"
                                       placeholder="{{ __('messages.search_placeholder') }}">
                                <button class="btn btn-custom md-size btn-primary btn-secondary-hover searchbox-btn"
                                        type="submit">
                                    <i class="ion-ios-search"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Categories (collapsible) --}}
                        <div class="sidebar-widget sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">{{ __('messages.categories') }}</h3>
                            <ul class="category-tree">
                                {{-- All products --}}
                                <li>
                                    <div class="cat-row">
                                        <a href="{{ route('products.index') }}"
                                           class="{{ !isset($category) ? 'active' : '' }}">
                                            {{ __('messages.all_products') }}
                                            <span>{{ \App\Models\Product::where('is_active', true)->count() }}</span>
                                        </a>
                                    </div>
                                </li>
                                {{-- Root categories --}}
                                @foreach($sidebarCategories as $cat)
                                    @php
                                        $isActive   = isset($category) && $category->id === $cat->id;
                                        $isParent   = isset($category) && $category->parent_id === $cat->id;
                                        $hasKids    = $cat->children->count() > 0;
                                        $isExpanded = $isActive || $isParent;
                                    @endphp
                                    <li>
                                        <div class="cat-row">
                                            <a href="{{ route('categories.show', $cat->getTranslation('slug', app()->getLocale())) }}"
                                               class="{{ $isActive ? 'active' : '' }}">
                                                {{ $cat->getTranslation('name', app()->getLocale()) }}
                                                <span>{{ $cat->active_products_count ?? 0 }}</span>
                                            </a>
                                            @if($hasKids)
                                                <button class="cat-toggle {{ $isExpanded ? 'open' : '' }}"
                                                        type="button">{{ $isExpanded ? '−' : '+' }}</button>
                                            @endif
                                        </div>
                                        @if($hasKids)
                                            <ul class="cat-children {{ $isExpanded ? 'show' : '' }}">
                                                @foreach($cat->children as $child)
                                                    <li>
                                                        <a href="{{ route('categories.show', $child->getTranslation('slug', app()->getLocale())) }}"
                                                           class="{{ isset($category) && $category->id === $child->id ? 'active' : '' }}">
                                                            {{ $child->getTranslation('name', app()->getLocale()) }}
                                                            <span>{{ $child->active_products_count ?? 0 }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Status filter --}}
                        <div class="sidebar-widget sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">{{ __('messages.status') }}</h3>
                            <ul class="category-tree">
                                @foreach(['available' => 'product_available', 'reserved' => 'product_reserved', 'unavailable' => 'product_unavailable'] as $val => $key)
                                    <li>
                                        <div class="cat-row">
                                            <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
                                               class="{{ request('status') === $val ? 'active' : '' }}">
                                                {{ __('messages.' . $key) }}
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                                @if(request('status'))
                                    <li style="padding:8px 0">
                                        <a href="{{ request()->fullUrlWithoutQuery('status') }}"
                                           style="font-size:13px;color:#ff5e13;text-decoration:none">
                                            × {{ __('messages.clear_filter') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        {{-- CTA --}}
                        <div class="sidebar-cta">
                            <i class="fa fa-phone"></i>
                            <h4>{{ __('messages.any_questions') }}</h4>
                            @if(\App\Models\Setting::get('site_phone'))
                                <a class="phone-link" href="tel:{{ \App\Models\Setting::get('site_phone') }}">
                                    {{ \App\Models\Setting::get('site_phone') }}
                                </a>
                            @endif
                            <a href="{{ route('contact') }}"
                               class="btn btn-custom btn-secondary btn-white-hover" style="width:100%">
                                {{ __('messages.inquiry') }}
                            </a>
                        </div>

                    </div>
                </div>

                {{-- ── Products ── --}}
                <div class="col-lg-9 order-lg-2 order-1">

                    {{-- Toolbar --}}
                    <div class="shop-toolbar">
                        <p class="showing-count mb-0">
                            @if($products->total() > 0)
                                <strong>{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong>
                                {{ __('messages.of') }}
                                <strong>{{ $products->total() }}</strong>
                                {{ __('messages.products') }}
                            @else
                                {{ __('messages.no_products') }}
                            @endif
                        </p>
                        <div class="shop-toolbar-right">
                            <form method="GET" id="sort-form"
                                  action="{{ isset($category)
                                      ? route('categories.show', $category->getTranslation('slug', app()->getLocale()))
                                      : route('products.index') }}">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                <select class="sort-select" name="sort"
                                        onchange="document.getElementById('sort-form').submit()">
                                    <option value="latest"     {{ request('sort','latest') === 'latest'     ? 'selected':'' }}>{{ __('messages.sort_latest') }}</option>
                                    <option value="featured"   {{ request('sort') === 'featured'   ? 'selected':'' }}>{{ __('messages.sort_featured') }}</option>
                                    <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected':'' }}>{{ __('messages.sort_price_asc') }}</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected':'' }}>{{ __('messages.sort_price_desc') }}</option>
                                    <option value="name_asc"   {{ request('sort') === 'name_asc'   ? 'selected':'' }}>{{ __('messages.sort_name_asc') }}</option>
                                </select>
                            </form>
                            <div class="view-btns">
                                <button class="view-grid active" title="{{ __('messages.grid_view') }}">
                                    <i class="fa fa-th"></i>
                                </button>
                                <button class="view-list" title="{{ __('messages.list_view') }}">
                                    <i class="fa fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if($products->count())
                        <div class="product-wrap row" id="products-container">
                            @foreach($products as $product)
                            @php
                                $cardAttrs = $product->attributes
                                    ->filter(fn($pa) => $pa->attribute?->show_in_card && $pa->attribute?->is_active)
                                    ->sortBy(fn($pa) => $pa->attribute?->sort_order ?? 999);
                                $locale = app()->getLocale();
                            @endphp

                                {{-- ══ GRID CARD ══ --}}
                                <div class="col-md-4 col-sm-6 mb-8 product-col">
                                    <div class="sc">
                                        <div class="sc-img">
                                            <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">
                                                <img src="{{ $product->medium_image_url ?? asset('assets/images/product/placeholder.jpg') }}"
                                                     alt="{{ $product->getTranslation('name', $locale) }}" loading="lazy">
                                            </a>
                                            <div class="sc-badges">
                                                @if($product->is_new)<span class="sc-b sc-b-new">{{ __('messages.new') }}</span>@endif
                                                @if($product->is_featured)<span class="sc-b sc-b-hot">{{ __('messages.featured') }}</span>@endif
                                                @if($product->status === 'sold')<span class="sc-b sc-b-sold">{{ __('messages.product_sold') }}</span>@endif
                                                @if($product->status === 'reserved')<span class="sc-b sc-b-res">{{ __('messages.product_reserved') }}</span>@endif
                                            </div>
                                        </div>
                                        <div class="sc-body">
                                            @if($product->primaryCategory())
                                                <span class="sc-cat">{{ $product->primaryCategory()->getTranslation('name', $locale) }}</span>
                                            @endif
                                            <h2 class="sc-name">
                                                <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">{{ $product->getTranslation('name', $locale) }}</a>
                                            </h2>
                                            @if($cardAttrs->isNotEmpty())
                                                <div class="sc-dims">
                                                    @foreach($cardAttrs as $pa)
                                                        <span class="sc-dim">
                                                            <span class="sc-dim-k">{{ $pa->attribute->getTranslation('label', $locale, false) ?: $pa->attribute->getTranslation('label', 'en', false) }}</span>
                                                            <span class="sc-dim-v">{{ $pa->display_value }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="sc-divider"></div>
                                            <div class="sc-prices">
                                                @if($product->price_on_request)
                                                    <span class="sc-price-req">{{ __('messages.price_on_request') }}</span>
                                                @else
                                                    @if($product->price)<span class="sc-price-rial">{{ number_format($product->price) }} {{ __('messages.currency_rial') }}</span>@endif
                                                    <div class="sc-price-sub">
                                                        @if($product->price_usd)<span class="sc-price-usd">${{ number_format($product->price_usd) }}</span>@endif
                                                        @if($product->price_eur)<span class="sc-price-eur">€{{ number_format($product->price_eur) }}</span>@endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="sc-foot">
                                                <span class="sc-status sc-status-{{ $product->status === 'available' ? 'av' : ($product->status === 'sold' ? 'so' : ($product->status === 'reserved' ? 're' : 'un')) }}">{{ $product->status_label }}</span>
                                                <div class="sc-actions">
                                                    <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}" class="sc-btn sc-btn-ink">{{ __('messages.view_details') }}</a>
                                                    <a href="{{ route('contact') }}?product={{ $product->sku }}" class="sc-btn sc-btn-primary">{{ __('messages.inquiry') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ══ LIST CARD ══ --}}
                                <div class="col-12 mb-5 product-col-list">
                                    <div class="pl">
                                        <div class="pl-img">
                                            <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">
                                                <img src="{{ $product->medium_image_url ?? asset('assets/images/product/placeholder.jpg') }}"
                                                     alt="{{ $product->getTranslation('name', $locale) }}" loading="lazy">
                                            </a>
                                        </div>
                                        <div class="pl-body">
                                            <div class="pl-info">
                                                @if($product->primaryCategory())
                                                    <span class="sc-cat">{{ $product->primaryCategory()->getTranslation('name', $locale) }}</span>
                                                @endif
                                                <h3 class="pl-name">
                                                    <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">{{ $product->getTranslation('name', $locale) }}</a>
                                                </h3>
                                                @if($product->getTranslation('short_description', $locale))
                                                    <p class="pl-desc">{{ Str::limit($product->getTranslation('short_description', $locale), 160) }}</p>
                                                @endif
                                                @if($cardAttrs->isNotEmpty())
                                                    <div class="sc-dims" style="margin-top:4px;">
                                                        @foreach($cardAttrs as $pa)
                                                            <span class="sc-dim">
                                                                <span class="sc-dim-k">{{ $pa->attribute->getTranslation('label', $locale, false) ?: $pa->attribute->getTranslation('label', 'en', false) }}</span>
                                                                <span class="sc-dim-v">{{ $pa->display_value }}</span>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="pl-right">
                                                <div>
                                                    @if($product->price_on_request)
                                                        <span class="sc-price-req">{{ __('messages.price_on_request') }}</span>
                                                    @else
                                                        @if($product->price)<div class="pl-price-rial">{{ number_format($product->price) }} {{ __('messages.currency_rial') }}</div>@endif
                                                        <div class="pl-price-sub">
                                                            @if($product->price_usd)${{ number_format($product->price_usd) }}@endif
                                                            @if($product->price_eur) · €{{ number_format($product->price_eur) }}@endif
                                                        </div>
                                                    @endif
                                                    <span class="sc-status sc-status-{{ $product->status === 'available' ? 'av' : ($product->status === 'sold' ? 'so' : ($product->status === 'reserved' ? 're' : 'un')) }}" style="margin-top:6px;display:inline-flex;">{{ $product->status_label }}</span>
                                                </div>
                                                <div class="pl-btns">
                                                    <a href="{{ route('contact') }}?product={{ $product->sku }}" class="pl-btn pl-btn-primary">{{ __('messages.inquiry') }}</a>
                                                    <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}" class="pl-btn pl-btn-ghost">{{ __('messages.view_details') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="col-lg-12 pt-10">
                            <div class="pagination-wrap">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>

                    @else
                        <div class="no-products-found">
                            <i class="ion-cube"></i>
                            <h4>{{ __('messages.no_products') }}</h4>
                            <p>{{ __('messages.no_products_desc') }}</p>
                            @if(request()->hasAny(['search','status']))
                                <a href="{{ isset($category)
                                       ? route('categories.show', $category->getTranslation('slug', app()->getLocale()))
                                       : route('products.index') }}"
                                   class="btn btn-custom btn-primary btn-secondary-hover mt-4">
                                    {{ __('messages.clear_filter') }}
                                </a>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Category tree toggle
        document.querySelectorAll('.cat-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const parent   = btn.closest('li');
                const children = parent.querySelector('.cat-children');
                const isOpen   = children.classList.contains('show');
                children.classList.toggle('show');
                btn.classList.toggle('open');
                btn.textContent = isOpen ? '+' : '−';
            });
        });

        // Grid / List view toggle
        const gridCols  = document.querySelectorAll('.product-col');
        const listCols  = document.querySelectorAll('.product-col-list');
        const btnGrid   = document.querySelector('.view-grid');
        const btnList   = document.querySelector('.view-list');
        const container = document.getElementById('products-container');

        btnGrid?.addEventListener('click', () => {
            gridCols.forEach(el => el.style.display = '');
            listCols.forEach(el => el.style.display = 'none');
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
        });

        btnList?.addEventListener('click', () => {
            gridCols.forEach(el => el.style.display = 'none');
            listCols.forEach(el => el.style.display = 'block');
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
        });
    </script>
@endpush
