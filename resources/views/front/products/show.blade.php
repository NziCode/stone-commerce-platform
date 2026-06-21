@extends('front.layouts.app')

@section('title',
    $product->getTranslation('name', app()->getLocale())
    . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
    <style>
        /* ── Gallery thumbnails ─────────────────────── */
        .product-thumb-slider { margin-top: 12px; }
        .product-thumb-slider .swiper-slide {
            cursor: pointer; opacity: 0.6; transition: opacity 0.2s;
            border: 2px solid transparent;
        }
        .product-thumb-slider .swiper-slide.active-thumb,
        .product-thumb-slider .swiper-slide:hover { opacity: 1; border-color: #ff5e13; }
        .product-thumb-slider .swiper-slide img { width: 100%; height: 80px; object-fit: cover; display: block; }

        /* ── Product info ───────────────────────────── */
        .product-status-badge {
            display: inline-block; padding: 4px 14px;
            font-size: 13px; font-weight: 600; margin-bottom: 12px;
        }
        .status-available   { background: #e8f7ee; color: #2d8a4e; }
        .status-unavailable { background: #fde8e8; color: #cc3333; }
        .status-reserved    { background: #fff3e0; color: #e8a000; }
        .status-sold        { background: #f0f0f0; color: #888; }

        .product-meta-row { display: flex; align-items: center; gap: 8px; padding: 6px 0; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
        .product-meta-row .meta-label { color: #888; min-width: 120px; flex-shrink: 0; }
        .product-meta-row .meta-value { color: #333; font-weight: 500; }

        /* ── Tabs ───────────────────────────────────── */
        .product-detail-tab .nav-pills { border-bottom: 2px solid #f0f0f0; gap: 4px; }
        .product-detail-tab .nav-link {
            color: #666; border-radius: 0; padding: 10px 20px;
            font-size: 15px; font-weight: 500; border-bottom: 2px solid transparent;
            margin-bottom: -2px;
        }
        .product-detail-tab .nav-link.active { color: #ff5e13; border-bottom-color: #ff5e13; background: none; }

        /* ── Attributes table ───────────────────────── */
        .attr-table { width: 100%; border-collapse: collapse; }
        .attr-table tr:nth-child(even) { background: #f9f9f9; }
        .attr-table th, .attr-table td { padding: 10px 14px; font-size: 14px; border-bottom: 1px solid #f0f0f0; text-align: start; }
        .attr-table th { color: #666; font-weight: 500; width: 40%; }
        .attr-table td { color: #333; font-weight: 600; }

        /* ── Inquiry form ───────────────────────────── */
        .inquiry-form input,
        .inquiry-form textarea {
            width: 100%; border: 1px solid #dee2e6;
            padding: 10px 14px; font-size: 14px; margin-bottom: 14px;
            font-family: inherit;
        }
        .inquiry-form textarea { height: 120px; resize: vertical; }

        /* ── Related products ───────────────────────── */
        .related-product-title { font-size: 32px; }
        .related-product-img img { height: 220px; object-fit: cover; width: 100%; }

        /* ── Sidebar ────────────────────────────────── */
        .sidebar-cta { background: #00225a; padding: 24px 20px; text-align: center; }
        .sidebar-cta i { font-size: 30px; color: #ff5e13; display: block; margin-bottom: 10px; }
        .sidebar-cta h4 { color: #fff; font-size: 15px; margin-bottom: 8px; }
        .sidebar-cta a.phone-link { color: #ff5e13; font-size: 17px; font-weight: 700; display: block; margin-bottom: 14px; text-decoration: none; }

        /* ── RTL ────────────────────────────────────── */
        [dir="rtl"] .product-meta-row .meta-label { text-align: right; }
        [dir="rtl"] .attr-table th, [dir="rtl"] .attr-table td { text-align: right; }
        [dir="rtl"] .product-detail-content { padding-right: 8px; padding-left: 0; }
    </style>
@endpush

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.products'),
        'title'    => $product->getTranslation('name', app()->getLocale()),
        'crumbs'   => array_filter([
            ['label' => __('messages.products'), 'url' => route('products.index')],
            $product->primaryCategory() ? ['label' => $product->primaryCategory()->getTranslation('name', app()->getLocale()), 'url' => route('categories.show', $product->primaryCategory()->getTranslation('slug', app()->getLocale()))] : null,
            ['label' => Str::limit($product->getTranslation('name', app()->getLocale()), 40)],
        ]),
    ])

    {{-- Product Detail --}}
    <div class="product-detail-area py-140">
        <div class="container">
            <div class="row">

                {{-- ── Main Content ── --}}
                <div class="col-lg-8">
                    <div class="product-detail-wrap row">

                        {{-- Gallery --}}
                        <div class="col-lg-6">
                            <div class="product-detail-img">
                                {{-- Main slider --}}
                                <div class="swiper-container product-detail-slider swiper-arrow swiper-arrow-sm-size with-bg_white">
                                    <div class="swiper-wrapper">
                                        @forelse($product->getMedia('gallery') as $media)
                                            <div class="swiper-slide">
                                                <div class="single-img zoom">
                                                    <img src="{{ $media->getUrl() }}"
                                                         alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                                </div>
                                            </div>
                                        @empty
                                            @if($product->medium_image_url)
                                                <div class="swiper-slide">
                                                    <div class="single-img zoom">
                                                        <img src="{{ $product->medium_image_url }}"
                                                             alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforelse
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>

                                {{-- Thumbnails --}}
                                @if($product->getMedia('gallery')->count() > 1)
                                    <div class="swiper-container product-thumb-slider mt-3">
                                        <div class="swiper-wrapper">
                                            @foreach($product->getMedia('gallery') as $media)
                                                <div class="swiper-slide">
                                                    <img src="{{ $media->getUrl('thumb') ?? $media->getUrl() }}"
                                                         alt="thumb">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Product Info --}}
                        <div class="col-lg-6 pt-9 pt-lg-0">
                            <div class="product-detail-content ps-1">

                                {{-- Status badge --}}
                                <span class="product-status-badge status-{{ $product->status }}">
                                    {{ $product->status_label }}
                                </span>

                                {{-- Name --}}
                                <h2 class="title mb-3">
                                    {{ $product->getTranslation('name', app()->getLocale()) }}
                                </h2>

                                {{-- Price --}}
                                <div class="price-box pb-5" style="border-bottom:1px solid #f0f0f0">
                                    @if($product->price_on_request)
                                        <span style="font-size:16px;color:#888;font-style:italic">
                                            {{ __('messages.price_on_request') }}
                                        </span>
                                    @elseif($product->price_usd)
                                        <span class="new-price">${{ number_format($product->price_usd) }}</span>
                                        @if($product->price)
                                            <span style="font-size:13px;color:#999;margin-inline-start:8px">
                                                / {{ number_format($product->price) }} {{ __('messages.currency_rial') }}
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                {{-- Short description --}}
                                @if($product->getTranslation('short_description', app()->getLocale()))
                                    <p class="short-desc mt-5 mb-5" style="font-size:14px;color:#555;line-height:1.7">
                                        {{ $product->getTranslation('short_description', app()->getLocale()) }}
                                    </p>
                                @endif

                                {{-- Meta info --}}
                                <div class="product-meta pt-3 pb-3">
                                    <div class="product-meta-row">
                                        <span class="meta-label">{{ __('admin.sku') }}</span>
                                        <span class="meta-value">{{ $product->sku }}</span>
                                    </div>
                                    @if($product->primaryCategory())
                                        <div class="product-meta-row">
                                            <span class="meta-label">{{ __('messages.categories') }}</span>
                                            <span class="meta-value">
                                                <a href="{{ route('categories.show', $product->primaryCategory()->getTranslation('slug', app()->getLocale())) }}"
                                                   style="color:#ff5e13">
                                                    {{ $product->primaryCategory()->getTranslation('name', app()->getLocale()) }}
                                                </a>
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- CTA buttons --}}
                                <div class="button-wrap d-flex gap-3 pt-5 flex-wrap">
                                    <a href="{{ route('contact') }}?product={{ $product->sku }}"
                                       class="btn btn-custom btn-primary btn-secondary-hover btn-lg rounded-0">
                                        {{ __('messages.inquiry') }}
                                    </a>
                                    @if($product->isAvailable())
                                        <form action="{{ route('cart.add', $product) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-custom btn-secondary btn-primary-hover btn-lg rounded-0">
                                                {{ __('messages.add_to_cart') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>

                            </div>
                        </div>

                        {{-- Tabs --}}
                        <div class="col-lg-12">
                            <div class="product-detail-tab pt-9">
                                <ul class="nav nav-pills" id="productTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-desc">
                                            {{ __('messages.about') }}
                                        </a>
                                    </li>
                                    @if($product->attributes->count())
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab-attrs">
                                                {{ __('admin.attributes') }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>

                                <div class="tab-content pt-7">

                                    {{-- Description tab --}}
                                    <div class="tab-pane fade show active" id="tab-desc">
                                        @if($product->getTranslation('description', app()->getLocale()))
                                            <div class="description-body">
                                                {!! $product->getTranslation('description', app()->getLocale()) !!}
                                            </div>
                                        @else
                                            <p style="color:#888;font-size:14px">—</p>
                                        @endif
                                    </div>

                                    {{-- Attributes tab --}}
                                    @if($product->attributes->count())
                                        <div class="tab-pane fade" id="tab-attrs">
                                            @php
                                                $locale = app()->getLocale();

                                                // Decode a JSON multi-lang field with locale fallback
                                                $pick = function($json) use ($locale) {
                                                    $arr = is_array($json) ? $json : json_decode((string) $json, true);
                                                    if (!is_array($arr)) return (string) $json;
                                                    return $arr[$locale] ?? $arr['en'] ?? $arr['fa'] ?? reset($arr) ?? '';
                                                };

                                                $rows = $product->attributes
                                                    ->sortBy('sort_order')
                                                    ->map(function($pa) use ($pick) {
                                                        $attr = $pa->attribute;
                                                        if (!$attr) return null;

                                                        $label = $pick($attr->label);
                                                        $group = $attr->group ? $pick($attr->group) : '';

                                                        // raw stored value, e.g. {"value":"gray"}
                                                        $rawValue = is_array($pa->value)
                                                            ? ($pa->value['value'] ?? '')
                                                            : (json_decode((string) $pa->value, true)['value'] ?? '');

                                                        $display = $rawValue;

                                                        // For select type: resolve the option's translated label
                                                        if ($attr->type === 'select' && $attr->options) {
                                                            $options = is_array($attr->options) ? $attr->options : json_decode((string) $attr->options, true);
                                                            foreach (($options ?? []) as $opt) {
                                                                if (($opt['key'] ?? null) === $rawValue) {
                                                                    $display = $pick($opt['label'] ?? $rawValue);
                                                                    break;
                                                                }
                                                            }
                                                        } elseif ($attr->type === 'bool') {
                                                            $display = $rawValue ? __('admin.yes') : __('admin.no');
                                                        }

                                                        return [
                                                            'label' => $label,
                                                            'group' => $group,
                                                            'value' => $display,
                                                            'unit'  => $attr->unit,
                                                        ];
                                                    })
                                                    ->filter(fn($r) => $r && $r['value'] !== '' && $r['value'] !== null);

                                                $grouped = $rows->groupBy('group');
                                            @endphp

                                            @foreach($grouped as $group => $items)
                                                @if($group)
                                                    <h5 style="color:#00225a;font-size:15px;font-weight:700;
                                                               margin:20px 0 8px;padding-bottom:6px;
                                                               border-bottom:2px solid #ff5e13;display:inline-block">
                                                        {{ $group }}
                                                    </h5>
                                                @endif
                                                <table class="attr-table" style="margin-bottom:16px">
                                                    <tbody>
                                                    @foreach($items as $row)
                                                        <tr>
                                                            <th>{{ $row['label'] }}</th>
                                                            <td>
                                                                {{ $row['value'] }}
                                                                @if($row['unit'])
                                                                    <span style="color:#999;font-size:12px;font-weight:400;margin-inline-start:4px">
                                                                            {{ $row['unit'] }}
                                                                        </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                        {{-- Related products --}}
                        @if($relatedProducts->count())
                            <div class="col-lg-12 pt-9">
                                <h2 class="related-product-title mb-7">
                                    @if(app()->getLocale() === 'fa') محصولات مرتبط
                                    @elseif(app()->getLocale() === 'ar') منتجات ذات صلة
                                    @elseif(app()->getLocale() === 'hi') संबंधित उत्पाद
                                    @elseif(app()->getLocale() === 'it') Prodotti Correlati
                                    @else Related Products
                                    @endif
                                </h2>
                                <div class="related-product-wrap row">
                                    @foreach($relatedProducts->take(4) as $related)
                                        <div class="col-md-3 col-sm-6 mb-6">
                                            <div class="product-item">
                                                <div class="product-img" style="overflow:hidden">
                                                    <a href="{{ route('products.show', $related->getTranslation('slug', app()->getLocale())) }}">
                                                        <img class="img-full"
                                                             src="{{ $related->medium_image_url ?? asset('assets/images/product/placeholder.jpg') }}"
                                                             alt="{{ $related->getTranslation('name', app()->getLocale()) }}"
                                                             style="height:200px;object-fit:cover">
                                                    </a>
                                                </div>
                                                <div class="product-content py-3">
                                                    <h4 class="title mb-1" style="font-size:15px">
                                                        <a href="{{ route('products.show', $related->getTranslation('slug', app()->getLocale())) }}"
                                                           style="color:#00225a">
                                                            {{ $related->getTranslation('name', app()->getLocale()) }}
                                                        </a>
                                                    </h4>
                                                    <span style="font-size:12px;color:#ff5e13;font-weight:600">
                                                        {{ $related->status_label }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- ── Sidebar ── --}}
                <div class="col-lg-4 pt-10 pt-lg-0
                    @if(in_array(app()->getLocale(), ['fa','ar'])) pe-lg-9 @else ps-lg-9 @endif">
                    <div class="sidebar-area">

                        {{-- Search --}}
                        <div class="sidebar-widget sidebar-searchbar sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">{{ __('messages.search') }}</h3>
                            <form class="sidebar-form" method="GET" action="{{ route('products.index') }}">
                                <input class="searchbox-input" type="text" name="search"
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
                                @foreach(\App\Models\Category::active()->roots()->with('children')->ordered()->get() as $cat)
                                    <li>
                                        <a href="{{ route('categories.show', $cat->getTranslation('slug', app()->getLocale())) }}"
                                           style="display:flex;justify-content:space-between;padding:9px 0;font-size:15px;color:#444;text-decoration:none;border-bottom:1px solid #f0f0f0"
                                           @if($product->categories->contains($cat)) style="color:#ff5e13;font-weight:600" @endif>
                                            {{ $cat->getTranslation('name', app()->getLocale()) }}
                                        </a>
                                    </li>
                                @endforeach
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
                            <a href="{{ route('contact') }}?product={{ $product->sku }}"
                               class="btn btn-custom btn-secondary btn-white-hover" style="width:100%">
                                {{ __('messages.inquiry') }}
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/swiper-bundle.min.js') }}"></script>
    <script>
        // Main product image slider
        const mainSlider = new Swiper('.product-detail-slider', {
            loop: true,
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });

        // Thumbnail slider
        const thumbSlider = document.querySelector('.product-thumb-slider');
        if (thumbSlider) {
            const thumbSwiper = new Swiper('.product-thumb-slider', {
                slidesPerView: 4,
                spaceBetween: 8,
                watchSlidesProgress: true,
            });
            mainSlider.controller.control = thumbSwiper;
            thumbSwiper.controller.control = mainSlider;

            // Active thumb highlight
            mainSlider.on('slideChange', () => {
                document.querySelectorAll('.product-thumb-slider .swiper-slide').forEach((s, i) => {
                    s.classList.toggle('active-thumb', i === mainSlider.realIndex);
                });
            });
        }
    </script>
@endpush
