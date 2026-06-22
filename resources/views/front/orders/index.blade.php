@extends('front.layouts.app')
@section('title', __('messages.orders') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.orders'),
        'crumbs'   => [['label' => __('messages.orders')]],
    ])

    @include('front.components.flash')

    <div class="mt-section">
        <div class="mt-container">

            @if($orders->count())
                <div class="sidebar-widget" style="padding:0;overflow:hidden">
                    <div style="overflow-x:auto">
                        <table class="table mb-0" style="min-width:600px">
                            <thead>
                                <tr style="background:var(--ink);color:#fff">
                                    @foreach([__('messages.order_number') ?? '#', __('messages.date') ?? 'Date', __('messages.total'), __('messages.status'), __('messages.payment_method'), ''] as $th)
                                        <th style="padding:.85rem 1.2rem;font-size:.72rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;border:0">{{ $th }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                @php
                                    $statusColor = match($order->status) {
                                        'confirmed','delivered' => 'background:#e9f9ef;color:#1f9d55',
                                        'pending','processing' => 'background:#fff8e1;color:#e0a400',
                                        'cancelled'            => 'background:#fdecea;color:#e0473a',
                                        'shipped'              => 'background:#e8f1fd;color:#123a7a',
                                        default                => 'background:var(--stone-100);color:var(--stone-700)',
                                    };
                                @endphp
                                <tr style="border-bottom:1px solid var(--stone-100)">
                                    <td style="padding:.9rem 1.2rem;vertical-align:middle;font-weight:700;color:var(--ink)">{{ $order->order_number }}</td>
                                    <td style="padding:.9rem 1.2rem;vertical-align:middle;font-size:.84rem;color:var(--stone-500)">{{ $order->created_at->format('Y/m/d') }}</td>
                                    <td style="padding:.9rem 1.2rem;vertical-align:middle;font-weight:700">{{ $order->formatted_total }}</td>
                                    <td style="padding:.9rem 1.2rem;vertical-align:middle">
                                        <span style="display:inline-flex;align-items:center;border-radius:999px;font-size:.7rem;font-weight:700;padding:.3rem .75rem;{{ $statusColor }}">{{ $order->status_label }}</span>
                                    </td>
                                    <td style="padding:.9rem 1.2rem;vertical-align:middle">
                                        <span style="display:inline-flex;align-items:center;border-radius:999px;font-size:.7rem;font-weight:700;padding:.3rem .75rem;background:{{ $order->payment_type === 'online' ? '#e9f9ef;color:#1f9d55' : '#e8f1fd;color:#123a7a' }}">
                                            {{ $order->payment_type === 'online' ? (__('messages.online') ?? 'Online') : (__('messages.receipt') ?? 'Receipt') }}
                                        </span>
                                    </td>
                                    <td style="padding:.9rem 1.2rem;vertical-align:middle">
                                        <a href="{{ route('orders.show', $order) }}" class="mt-btn mt-btn-outline mt-btn-sm">{{ __('messages.view') ?? 'View' }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="margin-top:1.4rem">{{ $orders->links() }}</div>

            @else
                <div style="text-align:center;padding:4rem 1rem">
                    <div style="width:80px;height:80px;border-radius:50%;background:var(--stone-100);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:var(--stone-500)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="36" height="36"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <h3 class="mt-heading" style="font-size:1.3rem;margin-bottom:.6rem">{{ __('messages.no_orders') ?? 'No orders yet' }}</h3>
                    <p style="color:var(--stone-500);margin-bottom:1.6rem">{{ __('messages.no_orders_desc') ?? '' }}</p>
                    <a href="{{ route('products.index') }}" class="mt-btn mt-btn-primary">{{ __('messages.all_products') }}</a>
                </div>
            @endif
        </div>
    </div>

@endsection
