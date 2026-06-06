@extends('front.layouts.app')
@section('title', 'سفارشات من')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'سفارشات من',
    ])

    <div class="py-140">
        <div class="container">
            @if($orders->count())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead style="background:#00225a;color:white">
                        <tr>
                            <th>شماره سفارش</th>
                            <th>تاریخ</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th>پرداخت</th>
                            <th>جزئیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->created_at->format('Y/m/d') }}</td>
                                <td>{{ $order->formatted_total }}</td>
                                <td>
                                    <span class="badge
                                        {{ $order->status === 'confirmed'  ? 'bg-success'   : '' }}
                                        {{ $order->status === 'pending'    ? 'bg-warning'   : '' }}
                                        {{ $order->status === 'cancelled'  ? 'bg-danger'    : '' }}
                                        {{ $order->status === 'delivered'  ? 'bg-primary'   : '' }}
                                        {{ $order->status === 'shipped'    ? 'bg-info'      : '' }}
                                        {{ !in_array($order->status, ['confirmed','pending','cancelled','delivered','shipped']) ? 'bg-secondary' : '' }}
                                    ">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $order->payment_type === 'online' ? 'bg-success' : 'bg-info' }}">
                                        {{ $order->payment_type === 'online' ? 'آنلاین' : 'فیش' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}"
                                       class="btn btn-sm btn-secondary btn-primary-hover rounded-0">
                                        مشاهده
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">{{ $orders->links() }}</div>
            @else
                <div class="text-center py-10">
                    <i class="fa fa-shopping-bag fa-4x text-muted mb-6 d-block"></i>
                    <h3 class="text-muted mb-4">سفارشی ثبت نشده است</h3>
                    <a href="{{ route('products.index') }}"
                       class="btn btn-custom btn-secondary btn-primary-hover">
                        مشاهده محصولات
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
