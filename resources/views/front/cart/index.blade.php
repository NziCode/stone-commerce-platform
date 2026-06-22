@extends('front.layouts.app')
@section('title', __('messages.cart') . ' — ' . \App\Models\Setting::get('site_name'))
@php $locale = app()->getLocale(); @endphp

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.shop'),
        'title'    => __('messages.cart'),
    ])

    @include('front.components.flash')

    <div class="mt-section">
        <div class="mt-container">

            @if($cart->isEmpty)
                <div style="text-align:center;padding:4rem 1rem">
                    <div style="width:80px;height:80px;border-radius:50%;background:var(--stone-100);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:var(--stone-500)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="36" height="36"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    <h3 class="mt-heading" style="font-size:1.3rem;margin-bottom:.6rem">{{ __('messages.empty_cart') }}</h3>
                    <p style="color:var(--stone-500);margin-bottom:1.6rem">{{ __('messages.empty_cart_desc') ?? '' }}</p>
                    <a href="{{ route('products.index') }}" class="mt-btn mt-btn-primary">{{ __('messages.all_products') }}</a>
                </div>

            @else
                <div class="row">
                    <div class="col-lg-8 mb-8 mb-lg-0">

                        <div class="sidebar-widget" style="padding:0;overflow:hidden">
                            <div style="overflow-x:auto">
                                <table class="table mb-0" style="min-width:500px">
                                    <thead>
                                        <tr style="background:var(--ink);color:#fff">
                                            <th style="padding:.9rem 1.2rem;font-size:.75rem;font-weight:700;letter-spacing:.03em;text-transform:uppercase;border:0">{{ __('messages.product') }}</th>
                                            <th style="padding:.9rem 1.2rem;font-size:.75rem;font-weight:700;letter-spacing:.03em;text-transform:uppercase;border:0">{{ __('messages.price') }}</th>
                                            <th style="padding:.9rem 1.2rem;font-size:.75rem;font-weight:700;letter-spacing:.03em;text-transform:uppercase;border:0">{{ __('messages.status') }}</th>
                                            <th style="padding:.9rem 1.2rem;border:0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cart->items as $item)
                                        <tr style="border-bottom:1px solid var(--stone-100)">
                                            <td style="padding:1rem 1.2rem;vertical-align:middle">
                                                <div style="display:flex;align-items:center;gap:.9rem">
                                                    <a href="{{ route('products.show', $item->product->getTranslation('slug', $locale)) }}" style="flex-shrink:0;width:64px;height:64px;border-radius:10px;overflow:hidden;display:block;background:var(--stone-50)">
                                                        <img src="{{ $item->product->thumb_url }}" style="width:100%;height:100%;object-fit:cover" alt="">
                                                    </a>
                                                    <div>
                                                        <a href="{{ route('products.show', $item->product->getTranslation('slug', $locale)) }}" style="font-weight:700;color:var(--ink);text-decoration:none;font-size:.92rem;display:block;margin-bottom:.2rem">
                                                            {{ $item->product->getTranslation('name', $locale) }}
                                                        </a>
                                                        @if($item->product->sku)
                                                            <span style="font-size:.75rem;color:var(--stone-500)">{{ $item->product->sku }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding:1rem 1.2rem;vertical-align:middle;font-weight:700;color:var(--ink)">
                                                {{ number_format($item->price) }}
                                                <small style="display:block;font-weight:500;font-size:.72rem;color:var(--stone-500)">{{ $item->currency }}</small>
                                            </td>
                                            <td style="padding:1rem 1.2rem;vertical-align:middle">
                                                <span class="mt-event-badge is-soon">{{ __('messages.reserved') ?? 'Reserved' }}</span>
                                            </td>
                                            <td style="padding:1rem 1.2rem;vertical-align:middle">
                                                <form action="{{ route('cart.remove', $item->product) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" style="width:34px;height:34px;border-radius:50%;border:1px solid var(--stone-200);background:#fff;color:var(--bad);display:flex;align-items:center;justify-content:center;cursor:pointer" title="{{ __('messages.remove') }}">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M3 6h18M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4h6v2"/></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div style="display:flex;justify-content:space-between;margin-top:1rem;gap:.6rem;flex-wrap:wrap">
                            <a href="{{ route('products.index') }}" class="mt-btn mt-btn-outline">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                {{ __('messages.continue_shopping') ?? 'Continue Shopping' }}
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="mt-btn mt-btn-outline" style="color:var(--bad);border-color:var(--bad)" onclick="return confirm('{{ __('messages.clear_cart_confirm') ?? 'Clear cart?' }}')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M3 6h18M19 6l-1 14H6L5 6"/></svg>
                                    {{ __('messages.clear_cart') ?? 'Clear Cart' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="sidebar-widget">
                            <h4 class="sidebar-title">{{ __('messages.order_summary') ?? 'Order Summary' }}</h4>

                            <div style="display:grid;gap:.6rem;margin-bottom:1.2rem">
                                <div style="display:flex;justify-content:space-between;font-size:.9rem">
                                    <span style="color:var(--stone-500)">{{ __('messages.subtotal') ?? 'Subtotal' }}</span>
                                    <span style="font-weight:600">{{ number_format($cart->subtotal) }}</span>
                                </div>
                                @if($cart->discount_amount > 0)
                                    <div style="display:flex;justify-content:space-between;font-size:.9rem;color:var(--ok)">
                                        <span>{{ __('messages.discount') ?? 'Discount' }}</span>
                                        <span style="font-weight:600">— {{ number_format($cart->discount_amount) }}</span>
                                    </div>
                                @endif
                                <div style="display:flex;justify-content:space-between;padding-top:.6rem;border-top:1px solid var(--stone-100)">
                                    <span style="font-weight:700;color:var(--ink)">{{ __('messages.total') }}</span>
                                    <span style="font-weight:800;font-size:1.15rem;color:var(--brand)">{{ number_format($cart->total) }}</span>
                                </div>
                            </div>

                            <form action="{{ route('cart.coupon') }}" method="POST" style="display:flex;gap:.5rem;margin-bottom:1.2rem">
                                @csrf
                                <input type="text" name="code" value="{{ $cart->coupon_code }}" placeholder="{{ __('messages.coupon_code') ?? 'Coupon code' }}" class="form-control" style="flex:1">
                                <button type="submit" class="mt-btn mt-btn-ink mt-btn-sm">{{ __('messages.apply') ?? 'Apply' }}</button>
                            </form>

                            @auth
                                <a href="{{ route('checkout.index') }}" class="mt-btn mt-btn-primary" style="width:100%;font-size:1rem">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    {{ __('messages.checkout') ?? 'Checkout' }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="mt-btn mt-btn-ink" style="width:100%">
                                    {{ __('messages.login_to_checkout') ?? 'Login to Checkout' }}
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
