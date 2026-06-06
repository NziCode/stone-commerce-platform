@extends('front.layouts.app')
@section('title', 'سفارش ' . $order->order_number)

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'سفارشات',
        'title'    => 'جزئیات سفارش',
    ])

    <div class="py-140">
        <div class="container">
            <div class="row">

                {{-- اطلاعات سفارش --}}
                <div class="col-lg-8 mb-8 mb-lg-0">

                    {{-- هدر --}}
                    <div class="d-flex justify-content-between align-items-center mb-6 p-4"
                         style="background:#f4f8ff">
                        <h4 class="mb-0">{{ $order->order_number }}</h4>
                        <span class="badge
                        {{ $order->status === 'confirmed' ? 'bg-success' : '' }}
                        {{ $order->status === 'pending'   ? 'bg-warning' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-danger'  : '' }}
                        {{ $order->status === 'delivered' ? 'bg-primary' : '' }}
                        {{ $order->status === 'shipped'   ? 'bg-info'    : '' }}
                        {{ !in_array($order->status, ['confirmed','pending','cancelled','delivered','shipped']) ? 'bg-secondary' : '' }}
                        " style="font-size:14px;padding:8px 16px">
                        {{ $order->status_label }}
                    </span>
                    </div>

                    {{-- محصولات --}}
                    <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                        <h5 class="mb-4">محصولات سفارش</h5>
                        @foreach($order->items as $item)
                            <div class="d-flex gap-3 mb-4 pb-4 border-bottom">
                                @if($item->product)
                                    <img src="{{ $item->product->thumb_url }}"
                                         style="width:70px;height:70px;object-fit:cover;flex-shrink:0">
                                @else
                                    <div style="width:70px;height:70px;background:#e9ecef;flex-shrink:0"></div>
                                @endif
                                <div class="flex-1">
                                    <h6 class="mb-1">{{ $item->product_name }}</h6>
                                    @if($item->product_sku)
                                        <small class="text-muted d-block">کد: {{ $item->product_sku }}</small>
                                    @endif
                                    <p class="mb-0 text-primary fw-bold mt-1">
                                        {{ $item->formatted_price }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td>جمع جزء:</td>
                                <td class="text-end">{{ number_format($order->subtotal) }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                                <tr class="text-success">
                                    <td>تخفیف:</td>
                                    <td class="text-end">- {{ number_format($order->discount_amount) }}</td>
                                </tr>
                            @endif
                            <tr class="fw-bold" style="font-size:16px">
                                <td>جمع کل:</td>
                                <td class="text-end text-primary">{{ $order->formatted_total }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- وضعیت پرداخت --}}
                    @if($order->payments->count())
                        <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                            <h5 class="mb-4">وضعیت پرداخت</h5>
                            @foreach($order->payments as $payment)
                                <div class="d-flex justify-content-between align-items-center p-3 mb-2"
                                     style="background:white">
                                    <div>
                                    <span class="fw-medium">
                                        {{ $payment->type === 'online' ? 'پرداخت آنلاین' : 'فیش بانکی' }}
                                    </span>
                                        <small class="text-muted d-block">
                                            {{ $payment->created_at->format('Y/m/d H:i') }}
                                        </small>
                                        @if($payment->isReceipt() && $payment->receipt_file_url)
                                            <a href="{{ $payment->receipt_file_url }}" target="_blank"
                                               class="btn btn-sm btn-outline-secondary rounded-0 mt-2">
                                                <i class="fa fa-download me-1"></i> دانلود فیش
                                            </a>
                                        @endif
                                    </div>
                                    <span class="badge
                                    {{ $payment->status === 'paid'    ? 'bg-success' : '' }}
                                    {{ $payment->status === 'pending' ? 'bg-warning' : '' }}
                                    {{ $payment->status === 'failed'  ? 'bg-danger'  : '' }}
                                    {{ !in_array($payment->status, ['paid','pending','failed']) ? 'bg-secondary' : '' }}
                                    ">
                                    {{ $payment->status_label }}
                                </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- tracking --}}
                    @if($order->tracking_code)
                        <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                            <h5 class="mb-3">کد رهگیری مرسوله</h5>
                            <p class="font-monospace fw-bold mb-0" style="font-size:18px">
                                {{ $order->tracking_code }}
                            </p>
                        </div>
                    @endif

                    {{-- دکمه لغو --}}
                    @if(in_array($order->status, ['pending', 'processing']))
                        <form action="{{ route('orders.cancel', $order) }}" method="POST"
                              onsubmit="return confirm('آیا از لغو سفارش مطمئن هستید؟')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-0">
                                <i class="fa fa-times me-1"></i> لغو سفارش
                            </button>
                        </form>
                    @endif
                </div>

                {{-- اطلاعات مشتری --}}
                <div class="col-lg-4">
                    <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                        <h5 class="mb-4">اطلاعات خریدار</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fa fa-user me-2 text-primary"></i>
                                {{ $order->customer_name }}
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-envelope me-2 text-primary"></i>
                                {{ $order->customer_email }}
                            </li>
                            @if($order->customer_phone)
                                <li class="mb-2">
                                    <i class="fa fa-phone me-2 text-primary"></i>
                                    {{ $order->customer_phone }}
                                </li>
                            @endif
                            @if($order->customer_company)
                                <li class="mb-2">
                                    <i class="fa fa-building me-2 text-primary"></i>
                                    {{ $order->customer_company }}
                                </li>
                            @endif
                            @if($order->customer_country)
                                <li class="mb-2">
                                    <i class="fa fa-globe me-2 text-primary"></i>
                                    {{ $order->customer_country }}
                                </li>
                            @endif
                            @if($order->customer_address)
                                <li class="mb-2">
                                    <i class="fa fa-map-marker me-2 text-primary"></i>
                                    {{ $order->customer_address }}
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div class="sidebar-single-item p-6" style="background:#f4f8ff">
                        <h5 class="mb-4">اطلاعات سفارش</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>تاریخ:</strong>
                                {{ $order->created_at->format('Y/m/d H:i') }}
                            </li>
                            <li class="mb-2">
                                <strong>روش پرداخت:</strong>
                                {{ $order->payment_type === 'online' ? 'آنلاین' : 'فیش بانکی' }}
                            </li>
                            @if($order->confirmed_at)
                                <li class="mb-2">
                                    <strong>تاریخ تأیید:</strong>
                                    {{ $order->confirmed_at->format('Y/m/d') }}
                                </li>
                            @endif
                            @if($order->customer_notes)
                                <li class="mb-2">
                                    <strong>یادداشت:</strong>
                                    <p class="text-muted mt-1 mb-0">{{ $order->customer_notes }}</p>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
