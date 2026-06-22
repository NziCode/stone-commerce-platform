@extends('front.layouts.app')
@section('title',
    $product->getTranslation('name', app()->getLocale())
    . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
@endpush

@php
    $locale   = app()->getLocale();
    $isWished = auth()->check() && auth()->user()->hasWishlisted($product->id);
    $phone    = \App\Models\Setting::get('site_phone');

    $pick = function($json) use ($locale) {
        $arr = is_array($json) ? $json : json_decode((string) $json, true);
        if (!is_array($arr)) return (string) $json;
        return $arr[$locale] ?? $arr['en'] ?? $arr['fa'] ?? reset($arr) ?? '';
    };

    $statusMap = [
        'available'   => ['bg' => '#e9f9ef', 'color' => '#1f9d55'],
        'reserved'    => ['bg' => '#fff8e1', 'color' => '#e0a400'],
        'sold'        => ['bg' => 'var(--stone-100)', 'color' => 'var(--stone-500)'],
        'unavailable' => ['bg' => '#fdecea', 'color' => '#e0473a'],
    ];
    $s = $statusMap[$product->status] ?? $statusMap['unavailable'];

    $galleryMedia = $product->getMedia('gallery');
    $hasGallery   = $galleryMedia->count() > 0;
@endphp

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.products'),
        'title'    => $product->getTranslation('name', $locale),
        'crumbs'   => array_filter([
            ['label' => __('messages.products'), 'url' => route('products.index')],
            $product->primaryCategory() ? [
                'label' => $product->primaryCategory()->getTranslation('name', $locale),
                'url'   => route('categories.show', $product->primaryCategory()->getTranslation('slug', $locale)),
            ] : null,
            ['label' => Str::limit($product->getTranslation('name', $locale), 40)],
        ]),
    ])

    @include('front.components.flash')

    <div class="mt-section">
        <div class="mt-container">
            <div class="row g-5">

                {{-- ══════════ LEFT — Gallery + Tabs ══════════ --}}
                <div class="col-lg-8">

                    {{-- ── Gallery ── --}}
                    <div class="row g-3 mb-5">
                        <div class="col-lg-8">
                            {{-- Main slider --}}
                            <div style="border-radius:var(--radius-lg);overflow:hidden;background:var(--stone-100);position:relative">
                                @if($hasGallery)
                                    <div class="swiper-container product-detail-slider" style="aspect-ratio:4/3">
                                        <div class="swiper-wrapper">
                                            @foreach($galleryMedia as $media)
                                                <div class="swiper-slide">
                                                    <img src="{{ $media->getUrl() }}"
                                                         alt="{{ $product->getTranslation('name', $locale) }}"
                                                         style="width:100%;height:100%;object-fit:cover;display:block">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($galleryMedia->count() > 1)
                                            <div class="swiper-button-next" style="color:var(--ink)"></div>
                                            <div class="swiper-button-prev" style="color:var(--ink)"></div>
                                        @endif
                                    </div>
                                @else
                                    <div style="aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;color:var(--stone-300)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" width="64" height="64"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Thumbnail strip --}}
                        @if($galleryMedia->count() > 1)
                            <div class="col-lg-4">
                                <div style="display:grid;gap:.5rem;height:100%;max-height:400px;overflow-y:auto">
                                    @foreach($galleryMedia as $i => $media)
                                        <div class="product-thumb-item{{ $i === 0 ? ' active-thumb' : '' }}"
                                             data-index="{{ $i }}"
                                             style="border-radius:10px;overflow:hidden;cursor:pointer;border:2px solid {{ $i === 0 ? 'var(--brand)' : 'transparent' }};transition:.2s;aspect-ratio:4/3">
                                            <img src="{{ $media->getUrl('thumb') ?? $media->getUrl() }}"
                                                 style="width:100%;height:100%;object-fit:cover;display:block">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ── Tabs: Description + Attributes ── --}}
                    <div class="sidebar-widget" style="padding:0;overflow:hidden">
                        {{-- Tab nav --}}
                        <div style="display:flex;border-bottom:2px solid var(--stone-100)">
                            <button class="product-tab-btn active"
                                    data-tab="tab-desc"
                                    style="padding:1rem 1.5rem;border:0;background:none;font-weight:700;font-size:.92rem;color:var(--brand);border-bottom:2px solid var(--brand);margin-bottom:-2px;cursor:pointer">
                                {{ __('messages.about') }}
                            </button>
                            @if($product->attributes->count())
                                <button class="product-tab-btn"
                                        data-tab="tab-attrs"
                                        style="padding:1rem 1.5rem;border:0;background:none;font-weight:600;font-size:.92rem;color:var(--stone-500);border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer">
                                    {{ __('admin.attributes') }}
                                </button>
                            @endif
                        </div>

                        {{-- Description --}}
                        <div id="tab-desc" class="product-tab-panel" style="padding:1.6rem">
                            @if($product->getTranslation('description', $locale))
                                <div class="mt-prose">
                                    {!! $product->getTranslation('description', $locale) !!}
                                </div>
                            @else
                                <p style="color:var(--stone-500);font-size:.9rem">—</p>
                            @endif
                        </div>

                        {{-- Attributes --}}
                        @if($product->attributes->count())
                            @php
                                $rows = $product->attributes
                                    ->sortBy('sort_order')
                                    ->map(function($pa) use ($pick) {
                                        $attr = $pa->attribute;
                                        if (!$attr) return null;
                                        $label = $pick($attr->label);
                                        $group = $attr->group ? $pick($attr->group) : '';
                                        $rawValue = is_array($pa->value)
                                            ? ($pa->value['value'] ?? '')
                                            : (json_decode((string) $pa->value, true)['value'] ?? '');
                                        $display = $rawValue;
                                        if ($attr->type === 'select' && $attr->options) {
                                            $opts = is_array($attr->options) ? $attr->options : json_decode((string) $attr->options, true);
                                            foreach (($opts ?? []) as $opt) {
                                                if (($opt['key'] ?? null) === $rawValue) {
                                                    $display = $pick($opt['label'] ?? $rawValue);
                                                    break;
                                                }
                                            }
                                        } elseif ($attr->type === 'bool') {
                                            $display = $rawValue ? __('admin.yes') : __('admin.no');
                                        }
                                        return ['label' => $label, 'group' => $group, 'value' => $display, 'unit' => $attr->unit];
                                    })
                                    ->filter(fn($r) => $r && $r['value'] !== '' && $r['value'] !== null);
                                $grouped = $rows->groupBy('group');
                            @endphp
                            <div id="tab-attrs" class="product-tab-panel" style="display:none;padding:1.6rem">
                                @foreach($grouped as $group => $items)
                                    @if($group)
                                        <h5 style="font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--stone-500);margin:1.4rem 0 .7rem">{{ $group }}</h5>
                                    @endif
                                    <table style="width:100%;border-collapse:collapse;margin-bottom:.5rem">
                                        <tbody>
                                        @foreach($items as $row)
                                            <tr style="border-bottom:1px solid var(--stone-100)">
                                                <th style="padding:.7rem .8rem;font-size:.84rem;font-weight:600;color:var(--stone-500);width:42%;text-align:start;background:var(--stone-50)">{{ $row['label'] }}</th>
                                                <td style="padding:.7rem .8rem;font-size:.9rem;font-weight:700;color:var(--ink)">
                                                    {{ $row['value'] }}
                                                    @if($row['unit'])
                                                        <span style="font-size:.76rem;font-weight:400;color:var(--stone-500);margin-inline-start:4px">{{ $row['unit'] }}</span>
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

                    {{-- ── Related Products ── --}}
                    @if($relatedProducts->count())
                        <div style="margin-top:2.4rem">
                            <div class="mt-section-head" style="margin-bottom:1.2rem">
                                <div>
                                    <span class="mt-eyebrow">{{ __('messages.products') }}</span>
                                    <h3 class="mt-heading" style="font-size:1.25rem;margin-top:.3rem">{{ __('messages.related') }}</h3>
                                </div>
                            </div>
                            <div class="row g-3">
                                @foreach($relatedProducts->take(4) as $related)
                                    <div class="col-sm-6 col-md-3">
                                        <a href="{{ route('products.show', $related->getTranslation('slug', $locale)) }}"
                                           class="mt-pcard" style="display:flex;flex-direction:column;text-decoration:none">
                                            <div class="mt-pcard-img" style="aspect-ratio:4/3">
                                                <img src="{{ $related->medium_image_url }}"
                                                     alt="{{ $related->getTranslation('name', $locale) }}"
                                                     loading="lazy">
                                            </div>
                                            <div class="mt-pcard-body" style="padding:.9rem 1rem">
                                                <p style="font-size:.84rem;font-weight:700;color:var(--ink);margin:0 0 .25rem;line-height:1.4">{{ Str::limit($related->getTranslation('name', $locale), 50) }}</p>
                                                <span style="font-size:.72rem;font-weight:700;color:var(--brand)">{{ $related->status_label }}</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ══════════ RIGHT — Info + Actions + Sidebar ══════════ --}}
                <div class="col-lg-4">
                    <div style="position:sticky;top:90px;display:grid;gap:1.2rem">

                        {{-- Product info card --}}
                        <div class="sidebar-widget">

                            {{-- Status --}}
                            <span style="display:inline-flex;align-items:center;gap:.4rem;border-radius:999px;font-size:.75rem;font-weight:700;padding:.35rem .85rem;background:{{ $s['bg'] }};color:{{ $s['color'] }};margin-bottom:.9rem">
                                <span style="width:6px;height:6px;border-radius:50%;background:currentColor"></span>
                                {{ $product->status_label }}
                            </span>

                            {{-- Name --}}
                            <h1 style="font-size:1.35rem;font-weight:800;color:var(--ink);line-height:1.3;margin:0 0 .9rem">
                                {{ $product->getTranslation('name', $locale) }}
                            </h1>

                            {{-- Price --}}
                            @if($product->price_on_request)
                                <p style="font-size:.9rem;color:var(--stone-500);font-style:italic;margin:0 0 1rem">{{ __('messages.price_on_request') }}</p>
                            @elseif($product->price_usd || $product->price)
                                <div style="margin-bottom:1rem">
                                    @if($product->price_usd)
                                        <span style="font-size:1.6rem;font-weight:800;color:var(--ink)">${{ number_format($product->price_usd) }}</span>
                                    @endif
                                    @if($product->price)
                                        <span style="font-size:.9rem;color:var(--stone-500);margin-inline-start:.5rem">{{ number_format($product->price) }} {{ __('messages.currency_rial') }}</span>
                                    @endif
                                </div>
                            @endif

                            {{-- Short description --}}
                            @if($product->getTranslation('short_description', $locale))
                                <p style="font-size:.88rem;color:var(--stone-700);line-height:1.8;margin:0 0 1rem;padding-bottom:1rem;border-bottom:1px solid var(--stone-100)">
                                    {{ $product->getTranslation('short_description', $locale) }}
                                </p>
                            @endif

                            {{-- Meta rows --}}
                            <div style="display:grid;gap:0;margin-bottom:1.2rem">
                                @if($product->sku)
                                    <div style="display:flex;justify-content:space-between;padding:.55rem 0;border-bottom:1px solid var(--stone-100);font-size:.84rem">
                                        <span style="color:var(--stone-500)">{{ __('admin.sku') }}</span>
                                        <span style="font-weight:600;color:var(--ink);font-family:monospace">{{ $product->sku }}</span>
                                    </div>
                                @endif
                                @if($product->primaryCategory())
                                    <div style="display:flex;justify-content:space-between;padding:.55rem 0;border-bottom:1px solid var(--stone-100);font-size:.84rem">
                                        <span style="color:var(--stone-500)">{{ __('messages.categories') }}</span>
                                        <a href="{{ route('categories.show', $product->primaryCategory()->getTranslation('slug', $locale)) }}" style="font-weight:600;color:var(--brand);text-decoration:none">
                                            {{ $product->primaryCategory()->getTranslation('name', $locale) }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- CTA Buttons --}}
                            <div style="display:grid;gap:.7rem">
                                <a href="{{ route('contact') }}?product={{ $product->sku }}"
                                   class="mt-btn mt-btn-primary" style="width:100%;justify-content:center">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                    {{ __('messages.inquiry') }}
                                </a>

                                @if($product->isAvailable())
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="mt-btn mt-btn-ink" style="width:100%;justify-content:center">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                            {{ __('messages.add_to_cart') }}
                                        </button>
                                    </form>
                                @endif

                                @auth
                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="mt-btn {{ $isWished ? 'mt-btn-primary' : 'mt-btn-outline' }}"
                                                style="width:100%;justify-content:center">
                                            <svg viewBox="0 0 24 24"
                                                 fill="{{ $isWished ? 'currentColor' : 'none' }}"
                                                 stroke="currentColor" stroke-width="2" width="16" height="16">
                                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                            </svg>
                                            {{ $isWished ? (__('messages.wishlisted') ?? 'Saved') : __('messages.wishlist') }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="mt-btn mt-btn-outline" style="width:100%;justify-content:center;font-size:.84rem;color:var(--stone-500)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                        {{ __('messages.login_to_wishlist') ?? __('messages.wishlist') }}
                                    </a>
                                @endauth
                            </div>
                        </div>

                        {{-- Contact CTA --}}
                        <div style="background:linear-gradient(160deg,var(--ink),var(--ink-2));border-radius:var(--radius);padding:1.4rem 1.5rem;text-align:center">
                            <div style="width:44px;height:44px;border-radius:50%;background:var(--brand-soft);color:var(--brand);display:flex;align-items:center;justify-content:center;margin:0 auto .8rem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <p style="color:rgba(255,255,255,.75);font-size:.84rem;margin:0 0 .8rem">{{ __('messages.any_questions') }}</p>
                            @if($phone)
                                <a href="tel:{{ $phone }}" style="display:block;color:var(--brand-2);font-size:1rem;font-weight:800;text-decoration:none;margin-bottom:1rem">{{ $phone }}</a>
                            @endif
                        </div>

                        {{-- Categories sidebar --}}
                        <div class="sidebar-widget">
                            <h4 class="sidebar-title">{{ __('messages.categories') }}</h4>
                            <ul style="list-style:none;margin:0;padding:0;display:grid;gap:0">
                                @foreach(\App\Models\Category::active()->roots()->with('children')->ordered()->get() as $cat)
                                    @php $isCurrent = $product->categories->contains($cat->id); @endphp
                                    <li>
                                        <a href="{{ route('categories.show', $cat->getTranslation('slug', $locale)) }}"
                                           style="display:flex;justify-content:space-between;align-items:center;padding:.65rem .1rem;border-bottom:1px solid var(--stone-100);text-decoration:none;font-size:.88rem;font-weight:{{ $isCurrent ? '700' : '500' }};color:{{ $isCurrent ? 'var(--brand)' : 'var(--stone-700)' }}">
                                            <span>{{ $cat->getTranslation('name', $locale) }}</span>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13" style="opacity:.4"><path d="M9 18l6-6-6-6"/></svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
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
    (function () {
        // ── Main gallery slider ──────────────────────
        const mainEl = document.querySelector('.product-detail-slider');
        let mainSwiper;
        if (mainEl) {
            mainSwiper = new Swiper('.product-detail-slider', {
                loop: true,
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            });
        }

        // ── Thumbnail click ──────────────────────────
        document.querySelectorAll('.product-thumb-item').forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                const idx = parseInt(this.dataset.index);
                if (mainSwiper) mainSwiper.slideToLoop(idx);
                document.querySelectorAll('.product-thumb-item').forEach(function (t) {
                    t.style.borderColor = 'transparent';
                    t.classList.remove('active-thumb');
                });
                this.style.borderColor = 'var(--brand)';
                this.classList.add('active-thumb');
            });
        });

        if (mainSwiper) {
            mainSwiper.on('slideChange', function () {
                const ri = mainSwiper.realIndex;
                document.querySelectorAll('.product-thumb-item').forEach(function (t, i) {
                    const active = i === ri;
                    t.style.borderColor = active ? 'var(--brand)' : 'transparent';
                    t.classList.toggle('active-thumb', active);
                });
            });
        }

        // ── Tab switching ────────────────────────────
        document.querySelectorAll('.product-tab-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.product-tab-btn').forEach(function (b) {
                    b.style.color = 'var(--stone-500)';
                    b.style.borderBottomColor = 'transparent';
                    b.classList.remove('active');
                });
                document.querySelectorAll('.product-tab-panel').forEach(function (p) {
                    p.style.display = 'none';
                });
                this.style.color = 'var(--brand)';
                this.style.borderBottomColor = 'var(--brand)';
                this.classList.add('active');
                var panel = document.getElementById(this.dataset.tab);
                if (panel) panel.style.display = 'block';
            });
        });
    })();
    </script>
@endpush
