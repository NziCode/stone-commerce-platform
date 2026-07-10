@extends('front.layouts.app')
@section('title', __('messages.order') . ' ' . $order->order_number)
@php $locale = app()->getLocale(); @endphp

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.order_details') ?? 'Order Details',
        'crumbs'   => [
            ['label' => __('messages.orders'), 'url' => route('orders.index')],
            ['label' => $order->order_number],
        ],
    ])

    @include('front.components.flash')

    <div class="mt-section">
        <div class="mt-container">
            <div class="row">

                <div class="col-lg-8 mb-8 mb-lg-0">

                    {{-- Header bar --}}
                    @php
                        $statusColor = match($order->status) {
                            'confirmed','delivered' => '#e9f9ef;color:#1f9d55',
                            'pending','processing' => '#fff8e1;color:#e0a400',
                            'cancelled'            => '#fdecea;color:#e0473a',
                            'shipped'              => '#e8f1fd;color:#123a7a',
                            default                => 'var(--stone-100);color:var(--stone-700)',
                        };
                    @endphp
                    <div style="display:flex;justify-content:space-between;align-items:center;background:var(--ink);color:#fff;border-radius:var(--radius) var(--radius) 0 0;padding:1.2rem 1.5rem;margin-bottom:0">
                        <div>
                            <span style="font-size:.75rem;opacity:.65;display:block;margin-bottom:.15rem">{{ __('messages.order_number') ?? 'Order' }}</span>
                            <strong style="font-size:1.1rem">{{ $order->order_number }}</strong>
                        </div>
                        <span style="border-radius:999px;font-size:.75rem;font-weight:700;padding:.4rem .9rem;background:{{ $statusColor }}">{{ $order->status_label }}</span>
                    </div>

                    {{-- Order items --}}
                    <div class="sidebar-widget" style="border-radius:0 0 var(--radius) var(--radius);margin-bottom:1.4rem">
                        <h5 class="sidebar-title" style="margin-bottom:1rem">{{ __('messages.order_items') ?? 'Items' }}</h5>
                        @foreach($order->items as $item)
                            <div style="display:flex;gap:.9rem;padding-bottom:1rem;margin-bottom:1rem;border-bottom:1px solid var(--stone-100);align-items:center">
                                @if($item->product)
                                    <a href="{{ route('products.show', $item->product->getTranslation('slug', $locale)) }}" style="flex-shrink:0;width:66px;height:66px;border-radius:10px;overflow:hidden;display:block;background:var(--stone-50)">
                                        <img src="{{ $item->product->thumb_url }}" style="width:100%;height:100%;object-fit:cover" alt="">
                                    </a>
                                @else
                                    <div style="flex-shrink:0;width:66px;height:66px;border-radius:10px;background:var(--stone-100)"></div>
                                @endif
                                <div style="flex:1">
                                    <p style="font-size:.9rem;font-weight:700;color:var(--ink);margin:0 0 .2rem">{{ $item->product_name }}</p>
                                    @if($item->product_sku)
                                        <span style="font-size:.74rem;color:var(--stone-500)">{{ $item->product_sku }}</span>
                                    @endif
                                </div>
                                <span style="font-weight:800;color:var(--brand)">{{ $item->formatted_price }}</span>
                            </div>
                        @endforeach

                        <div style="display:grid;gap:.4rem;padding-top:.4rem">
                            <div style="display:flex;justify-content:space-between;font-size:.88rem">
                                <span style="color:var(--stone-500)">{{ __('messages.subtotal') ?? 'Subtotal' }}</span>
                                <span style="font-weight:600">{{ number_format($order->subtotal) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div style="display:flex;justify-content:space-between;font-size:.88rem;color:var(--ok)">
                                    <span>{{ __('messages.discount') ?? 'Discount' }}</span>
                                    <span>— {{ number_format($order->discount_amount) }}</span>
                                </div>
                            @endif
                            <div style="display:flex;justify-content:space-between;padding-top:.6rem;border-top:1px solid var(--stone-100)">
                                <span style="font-weight:700;color:var(--ink)">{{ __('messages.total') }}</span>
                                <span style="font-weight:800;font-size:1.1rem;color:var(--brand)">{{ $order->formatted_total }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Payments --}}
                    @if($order->payments->count())
                        <div class="sidebar-widget" style="margin-bottom:1.4rem">
                            <h5 class="sidebar-title">{{ __('messages.payment_status') ?? 'Payment Status' }}</h5>
                            @foreach($order->payments as $payment)
                                @php
                                    $pColor = match($payment->status) {
                                        'paid'    => 'background:#e9f9ef;color:#1f9d55',
                                        'pending' => 'background:#fff8e1;color:#e0a400',
                                        'failed'  => 'background:#fdecea;color:#e0473a',
                                        default   => 'background:var(--stone-100);color:var(--stone-700)',
                                    };
                                @endphp
                                <div style="display:flex;justify-content:space-between;align-items:center;background:var(--stone-50);border-radius:10px;padding:.9rem 1rem;margin-bottom:.6rem">
                                    <div>
                                        <strong style="font-size:.88rem;color:var(--ink);display:block">{{ $payment->type === 'online' ? (__('messages.online_payment') ?? 'Online') : (__('messages.receipt_payment') ?? 'Receipt') }}</strong>
                                        <span style="font-size:.74rem;color:var(--stone-500)">{{ $payment->created_at->format('Y/m/d H:i') }}</span>
                                        @if($payment->isReceipt() && $payment->receipt_file_url)
                                            <a href="{{ $payment->receipt_file_url }}" target="_blank" class="mt-btn mt-btn-outline mt-btn-sm" style="margin-top:.4rem;display:inline-flex">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                                {{ __('messages.download_receipt') ?? 'Receipt' }}
                                            </a>
                                        @endif
                                    </div>
                                    <span style="border-radius:999px;font-size:.7rem;font-weight:700;padding:.3rem .75rem;{{ $pColor }}">{{ $payment->status_label }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tracking --}}
                    @if($order->tracking_code)
                        <div class="sidebar-widget" style="margin-bottom:1.4rem;border-inline-start:4px solid var(--brand)">
                            <h5 class="sidebar-title">{{ __('messages.tracking_code') ?? 'Tracking Code' }}</h5>
                            <p style="font-family:monospace;font-size:1.35rem;font-weight:800;color:var(--ink);margin:0;letter-spacing:.05em">{{ $order->tracking_code }}</p>
                        </div>
                    @endif

                    {{-- Cancel --}}
                    @if(in_array($order->status, ['pending', 'processing']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('{{ __('messages.cancel_order_confirm') ?? 'Cancel order?' }}')">
                            @csrf
                            <button type="submit" class="mt-btn mt-btn-outline" style="color:var(--bad);border-color:var(--bad)">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/></svg>
                                {{ __('messages.cancel_order') ?? 'Cancel Order' }}
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    <div class="sidebar-widget" style="margin-bottom:1.4rem">
                        <h5 class="sidebar-title">{{ __('messages.buyer_info') ?? 'Buyer Info' }}</h5>
                        <ul style="list-style:none;margin:0;padding:0;display:grid;gap:.7rem">
                            @foreach([
                                ['icon'=>'<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>', 'val'=>$order->customer_name],
                                ['icon'=>'<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/>', 'val'=>$order->customer_email],
                                $order->customer_phone ? ['icon'=>'<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>', 'val'=>display_phone($order->customer_phone)] : null,
                                $order->customer_company ? ['icon'=>'<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>', 'val'=>$order->customer_company] : null,
                                $order->customer_country ? ['icon'=>'<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 0 20 15.3 15.3 0 0 1 0-20z"/>', 'val'=>$order->customer_country] : null,
                                $order->customer_address ? ['icon'=>'<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>', 'val'=>$order->customer_address] : null,
                            ] as $row)
                                @if($row)
                                    <li style="display:flex;align-items:flex-start;gap:.6rem;font-size:.85rem;color:var(--stone-700)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" style="color:var(--brand);flex-shrink:0;margin-top:2px">{!! $row['icon'] !!}</svg>
                                        <span>{{ $row['val'] }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <div class="sidebar-widget">
                        <h5 class="sidebar-title">{{ __('messages.order_info') ?? 'Order Info' }}</h5>
                        <ul style="list-style:none;margin:0;padding:0;display:grid;gap:.6rem;font-size:.85rem">
                            <li style="display:flex;justify-content:space-between"><span style="color:var(--stone-500)">{{ __('messages.date') ?? 'Date' }}</span><span style="font-weight:600">{{ $order->created_at->format('Y/m/d') }}</span></li>
                            <li style="display:flex;justify-content:space-between"><span style="color:var(--stone-500)">{{ __('messages.payment_method') }}</span><span style="font-weight:600">{{ $order->payment_type === 'online' ? (__('messages.online') ?? 'Online') : (__('messages.receipt') ?? 'Receipt') }}</span></li>
                            @if($order->confirmed_at)
                                <li style="display:flex;justify-content:space-between"><span style="color:var(--stone-500)">{{ __('messages.confirmed_at') ?? 'Confirmed' }}</span><span style="font-weight:600">{{ $order->confirmed_at->format('Y/m/d') }}</span></li>
                            @endif
                        </ul>
                        @if($order->customer_notes)
                            <div style="margin-top:1rem;padding:.8rem 1rem;background:var(--stone-50);border-radius:10px;font-size:.84rem;color:var(--stone-700)">
                                <strong style="display:block;margin-bottom:.3rem;color:var(--ink)">{{ __('messages.notes') ?? 'Notes' }}</strong>
                                {{ $order->customer_notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
