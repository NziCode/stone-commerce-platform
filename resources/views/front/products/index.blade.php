@extends('front.layouts.app')

@section('title', isset($category)
    ? $category->getTranslation('name', app()->getLocale()) . ' — ' . \App\Models\Setting::get('site_name')
    : __('messages.products') . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <style>
        /* ── Toolbar ────────────────────────────────── */
        .shop-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 14px 20px;
            background: #f4f8ff;
            margin-bottom: 30px;
        }
        .shop-toolbar .showing-count { font-size: 14px; color: #666; }
        .shop-toolbar .showing-count strong { color: #00225a; }
        .shop-toolbar-right { display: flex; align-items: center; gap: 12px; }
        .sort-select {
            border: 1px solid #dee2e6;
            padding: 8px 14px;
            font-size: 14px;
            background: #fff;
            color: #333;
            cursor: pointer;
            min-width: 180px;
        }
        .view-btns button {
            width: 36px; height: 36px;
            border: 1px solid #dee2e6;
            background: #fff;
            color: #999;
            cursor: pointer;
            transition: all 0.2s;
        }
        .view-btns button.active,
        .view-btns button:hover { background: #00225a; color: #fff; border-color: #00225a; }

        /* ── Product Card ───────────────────────────── */
        .product-item { transition: box-shadow 0.3s ease; height: 100%; }
        .product-item:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.1); }

        .product-img {
            position: relative;
            overflow: hidden;
            background: #f9f9f9;
        }
        .product-img a { display: block; }
        .product-img img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            transition: transform 0.4s ease;
            display: block;
        }
        .product-item:hover .product-img img { transform: scale(1.04); }

        /* ── Add action overlay ─────────────────────── */
        .product-img .add-action {
            position: absolute;
            bottom: -80px;
            left: 0;
            width: 100%;
            background: rgba(0,34,90,0.92);
            padding: 10px;
            display: flex;
            align-items: stretch;
            gap: 6px;
            transition: bottom 0.3s ease;
            z-index: 3;
            box-sizing: border-box;
        }
        .product-item:hover .product-img .add-action { bottom: 0; }

        .product-img .add-action .btn-inquiry,
        .product-img .add-action .btn-detail {
            display: block;
            text-align: center;
            padding: 8px 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            line-height: 1.4;
        }
        .product-img .add-action .btn-inquiry {
            flex: 1;
            background: #ff5e13;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .product-img .add-action .btn-inquiry:hover { background: #e04d00; color: #fff; }
        .product-img .add-action .btn-detail {
            flex: 1;
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.4);
        }
        .product-img .add-action .btn-detail:hover { background: rgba(255,255,255,0.25); color: #fff; }

        /* ── Badges ─────────────────────────────────── */
        .product-badges {
            position: absolute;
            top: 10px;
            inset-inline-start: 10px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            z-index: 2;
        }
        .product-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .badge-new      { background: #00225a; color: #fff; }
        .badge-featured { background: #ff5e13; color: #fff; }
        .badge-sold     { background: #888; color: #fff; }
        .badge-reserved { background: #e8a000; color: #fff; }

        /* ── Product content ────────────────────────── */
        .product-content { padding: 14px 4px 8px; }
        .product-content .category-label {
            font-size: 12px; color: #ff5e13;
            font-weight: 500; display: block; margin-bottom: 4px;
        }
        .product-content .title {
            font-size: 17px; font-weight: 600;
            margin-bottom: 6px; line-height: 1.3;
        }
        .product-content .title a { color: #00225a; }
        .product-content .title a:hover { color: #ff5e13; }
        .product-content .price-box { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .product-content .new-price { color: #ff5e13; font-size: 18px; font-weight: 700; }
        .product-content .price-on-request { font-size: 13px; color: #888; font-style: italic; }
        .product-content .status-label { font-size: 12px; font-weight: 600; margin-top: 4px; display: block; }
        .status-available   { color: #2d8a4e; }
        .status-unavailable { color: #cc3333; }
        .status-reserved    { color: #e8a000; }
        .status-sold        { color: #888; }

        /* ── List View ──────────────────────────────── */
        .product-col-list { display: none; }
        .products-list-view .product-col { display: none !important; }
        .products-list-view .product-col-list { display: block; }
        .product-card-list {
            display: flex;
            gap: 20px;
            padding: 20px;
            border: 1px solid #eee;
            margin-bottom: 16px;
            transition: box-shadow 0.3s;
        }
        .product-card-list:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .product-card-list .list-img {
            width: 200px; flex-shrink: 0;
        }
        .product-card-list .list-img img {
            width: 100%; height: 180px; object-fit: cover; display: block;
        }
        .product-card-list .list-body { flex: 1; padding: 4px 0; }
        .product-card-list .list-body .desc {
            font-size: 14px; color: #666; margin: 8px 0 12px; line-height: 1.6;
        }
        .product-card-list .list-body .list-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }

        /* ── Sidebar ────────────────────────────────── */
        .active-filter-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: #00225a; color: #fff;
            padding: 4px 10px; font-size: 12px; margin-bottom: 6px;
        }
        .active-filter-tag a { color: rgba(255,255,255,0.8); text-decoration: none; font-size: 14px; }
        .sidebar-cta {
            background: #00225a; padding: 24px 20px; text-align: center;
        }
        .sidebar-cta i { font-size: 30px; color: #ff5e13; display: block; margin-bottom: 10px; }
        .sidebar-cta h4 { color: #fff; font-size: 15px; margin-bottom: 8px; }
        .sidebar-cta a.phone-link {
            color: #ff5e13; font-size: 17px; font-weight: 700;
            display: block; margin-bottom: 14px; text-decoration: none;
        }

        /* ── No results ─────────────────────────────── */
        .no-products-found { text-align: center; padding: 60px 20px; color: #888; }
        .no-products-found i { font-size: 48px; color: #dee2e6; display: block; margin-bottom: 16px; }
        .no-products-found h4 { color: #555; margin-bottom: 8px; }

        /* ── RTL ────────────────────────────────────── */
        [dir="rtl"] .shop-toolbar        { flex-direction: row-reverse; }
        [dir="rtl"] .shop-toolbar-right  { flex-direction: row-reverse; }
        [dir="rtl"] .product-card-list   { flex-direction: row-reverse; }
        [dir="rtl"] .sidebar-form .searchbox-input {
            padding-right: 20px; padding-left: 55px; text-align: right;
        }
        [dir="rtl"] .sidebar-form .searchbox-btn { right: auto; left: 10px; }
        [dir="rtl"] .sidebar-blog-categories ul li a { flex-direction: row; }

        /* hover در RTL — هر دو دکمه کنار هم، بدون row-reverse */
        [dir="rtl"] .product-img .add-action { flex-direction: row; }

        /* ── Breadcrumb ─────────────────────────────── */
        .breadcrumb-area .breadcrumb-content .breadcrumb {
            background: none; padding: 0; margin: 10px 0 0;
            display: flex; align-items: center; justify-content: center;
            flex-wrap: wrap; gap: 0; list-style: none;
        }
        .breadcrumb-area .breadcrumb-item a { color: rgba(255,255,255,0.7); }
        .breadcrumb-area .breadcrumb-item.active { color: #ff5e13; }
        .breadcrumb-area .breadcrumb-item + .breadcrumb-item::before {
            content: '/'; color: rgba(255,255,255,0.4); padding: 0 8px;
        }
        /* در RTL ترتیب آیتم‌ها خودشان برعکسه — separator نباید تغییر کنه */
        [dir="rtl"] .breadcrumb-area .breadcrumb { flex-direction: row; }
        [dir="rtl"] .breadcrumb-area .breadcrumb-item + .breadcrumb-item::before {
            content: '/';
        }
    </style>
@endpush

@section('content')

    {{-- Breadcrumb --}}
    <div class="breadcrumb-area breadcrumb-height"
         data-bg-image="{{ asset('assets/images/breadcrumb/bg/1.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <span class="breadcrumb-sub-title text-white">{{ __('messages.products') }}</span>
                <h1 class="breadcrumb-title mb-1 text-white">
                    {{ isset($category)
                        ? $category->getTranslation('name', app()->getLocale())
                        : __('messages.all_products') }}
                </h1>
                @if(isset($category) && $category->getTranslation('description', app()->getLocale()))
                    <p class="breadcrumb-desc font-size-20">
                        {{ Str::limit($category->getTranslation('description', app()->getLocale()), 120) }}
                    </p>
                @endif
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">{{ __('messages.home') }}</a>
                    </li>
                    <li class="breadcrumb-item {{ !isset($category) ? 'active' : '' }}">
                        @if(isset($category))
                            <a href="{{ route('products.index') }}">{{ __('messages.products') }}</a>
                        @else
                            {{ __('messages.products') }}
                        @endif
                    </li>
                    @if(isset($category) && $category->parent)
                        <li class="breadcrumb-item">
                            <a href="{{ route('categories.show', $category->parent->getTranslation('slug', app()->getLocale())) }}">
                                {{ $category->parent->getTranslation('name', app()->getLocale()) }}
                            </a>
                        </li>
                    @endif
                    @if(isset($category))
                        <li class="breadcrumb-item active">
                            {{ $category->getTranslation('name', app()->getLocale()) }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

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

                        {{-- Categories --}}
                        <div class="sidebar-widget sidebar-blog-categories sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">{{ __('messages.categories') }}</h3>
                            <ul>
                                <li>
                                    <a href="{{ route('products.index') }}"
                                       class="{{ !isset($category) ? 'active' : '' }}">
                                        {{ __('messages.all_products') }}
                                        <span>{{ \App\Models\Product::where('is_active', true)->count() }}</span>
                                    </a>
                                </li>
                                @foreach($sidebarCategories as $cat)
                                    <li>
                                        <a href="{{ route('categories.show', $cat->getTranslation('slug', app()->getLocale())) }}"
                                           class="{{ isset($category) && $category->id === $cat->id ? 'active' : '' }}">
                                            {{ $cat->getTranslation('name', app()->getLocale()) }}
                                            <span>{{ $cat->active_products_count ?? 0 }}</span>
                                        </a>
                                    </li>
                                    @if($cat->children->count() && isset($category) && ($category->id === $cat->id || $category->parent_id === $cat->id))
                                        @foreach($cat->children as $child)
                                            <li style="padding-inline-start:16px">
                                                <a href="{{ route('categories.show', $child->getTranslation('slug', app()->getLocale())) }}"
                                                   class="{{ isset($category) && $category->id === $child->id ? 'active' : '' }}"
                                                   style="font-size:14px">
                                                    {{ $child->getTranslation('name', app()->getLocale()) }}
                                                    <span>{{ $child->active_products_count ?? 0 }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        {{-- Status filter --}}
                        <div class="sidebar-widget sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">{{ __('messages.status') }}</h3>
                            <ul>
                                @foreach(['available' => 'product_available', 'reserved' => 'product_reserved', 'unavailable' => 'product_unavailable'] as $val => $key)
                                    <li>
                                        <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
                                           class="{{ request('status') === $val ? 'active' : '' }}"
                                           style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;font-size:15px;color:#444;border-bottom:1px solid #f0f0f0">
                                            {{ __('messages.' . $key) }}
                                        </a>
                                    </li>
                                @endforeach
                                @if(request('status'))
                                    <li style="padding-top:8px">
                                        <a href="{{ request()->fullUrlWithoutQuery('status') }}"
                                           style="font-size:13px;color:#ff5e13">
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
                                    <option value="latest"     {{ request('sort','latest') === 'latest'     ? 'selected' : '' }}>{{ __('messages.sort_latest') }}</option>
                                    <option value="featured"   {{ request('sort') === 'featured'   ? 'selected' : '' }}>{{ __('messages.sort_featured') }}</option>
                                    <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>{{ __('messages.sort_price_asc') }}</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ __('messages.sort_price_desc') }}</option>
                                    <option value="name_asc"   {{ request('sort') === 'name_asc'   ? 'selected' : '' }}>{{ __('messages.sort_name_asc') }}</option>
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

                                {{-- Grid card --}}
                                <div class="col-md-4 col-sm-6 mb-8 product-col">
                                    <div class="product-item">
                                        <div class="product-img">
                                            <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                                <img src="{{ $product->medium_image_url ?? asset('assets/images/product/placeholder.jpg') }}"
                                                     alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                            </a>
                                            <div class="product-badges">
                                                @if($product->is_new)
                                                    <span class="product-badge badge-new">{{ __('messages.new') }}</span>
                                                @endif
                                                @if($product->is_featured)
                                                    <span class="product-badge badge-featured">{{ __('messages.featured') }}</span>
                                                @endif
                                                @if($product->status === 'sold')
                                                    <span class="product-badge badge-sold">{{ __('messages.product_sold') }}</span>
                                                @endif
                                                @if($product->status === 'reserved')
                                                    <span class="product-badge badge-reserved">{{ __('messages.product_reserved') }}</span>
                                                @endif
                                            </div>
                                            <div class="add-action">
                                                <a class="btn-inquiry"
                                                   href="{{ route('contact') }}?product={{ $product->sku }}">
                                                    {{ __('messages.inquiry') }}
                                                </a>
                                                <a class="btn-detail"
                                                   href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                                    {{ __('messages.view_details') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            @if($product->primaryCategory())
                                                <span class="category-label">
                                                    {{ $product->primaryCategory()->getTranslation('name', app()->getLocale()) }}
                                                </span>
                                            @endif
                                            <h2 class="title">
                                                <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                                    {{ $product->getTranslation('name', app()->getLocale()) }}
                                                </a>
                                            </h2>
                                            <div class="price-box">
                                                @if($product->price_on_request)
                                                    <span class="price-on-request">{{ __('messages.price_on_request') }}</span>
                                                @elseif($product->price_usd)
                                                    <span class="new-price">${{ number_format($product->price_usd) }}</span>
                                                @elseif($product->price)
                                                    <span class="new-price">{{ number_format($product->price) }} {{ __('messages.currency_rial') }}</span>
                                                @endif
                                            </div>
                                            <span class="status-label status-{{ $product->status }}">
                                                {{ $product->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- List card --}}
                                <div class="col-12 mb-4 product-col-list">
                                    <div class="product-card-list">
                                        <div class="list-img">
                                            <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                                                <img src="{{ $product->medium_image_url ?? asset('assets/images/product/placeholder.jpg') }}"
                                                     alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                            </a>
                                        </div>
                                        <div class="list-body">
                                            @if($product->primaryCategory())
                                                <span class="category-label">
                                                    {{ $product->primaryCategory()->getTranslation('name', app()->getLocale()) }}
                                                </span>
                                            @endif
                                            <h3 class="title" style="font-size:20px">
                                                <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}" style="color:#00225a">
                                                    {{ $product->getTranslation('name', app()->getLocale()) }}
                                                </a>
                                            </h3>
                                            @if($product->getTranslation('short_description', app()->getLocale()))
                                                <p class="desc">
                                                    {{ Str::limit($product->getTranslation('short_description', app()->getLocale()), 150) }}
                                                </p>
                                            @endif
                                            <div class="price-box mb-2">
                                                @if($product->price_on_request)
                                                    <span class="price-on-request">{{ __('messages.price_on_request') }}</span>
                                                @elseif($product->price_usd)
                                                    <span class="new-price">${{ number_format($product->price_usd) }}</span>
                                                @endif
                                            </div>
                                            <span class="status-label status-{{ $product->status }} d-block mb-2">
                                                {{ $product->status_label }}
                                            </span>
                                            <div class="list-actions">
                                                <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}"
                                                   class="btn btn-custom md-size btn-primary btn-secondary-hover">
                                                    {{ __('messages.view_details') }}
                                                </a>
                                                <a href="{{ route('contact') }}?product={{ $product->sku }}"
                                                   class="btn btn-custom md-size btn-secondary btn-primary-hover">
                                                    {{ __('messages.inquiry') }}
                                                </a>
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
                                <a href="{{ isset($category) ? route('categories.show', $category->getTranslation('slug', app()->getLocale())) : route('products.index') }}"
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
        const gridCols  = document.querySelectorAll('.product-col');
        const listCols  = document.querySelectorAll('.product-col-list');
        const btnGrid   = document.querySelector('.view-grid');
        const btnList   = document.querySelector('.view-list');
        const container = document.getElementById('products-container');

        btnGrid?.addEventListener('click', () => {
            gridCols.forEach(el => el.style.display = '');
            listCols.forEach(el => el.style.display = 'none');
            container.classList.remove('products-list-view');
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
        });

        btnList?.addEventListener('click', () => {
            gridCols.forEach(el => el.style.display = 'none');
            listCols.forEach(el => el.style.display = '');
            container.classList.add('products-list-view');
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
        });
    </script>
@endpush
