@extends('front.layouts.app')
@section('title', 'سبد خرید')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'خرید',
        'title'    => 'سبد خرید',
    ])

    <div class="py-140">
        <div class="container">

            @if($cart->isEmpty)
                <div class="text-center py-10">
                    <i class="fa fa-shopping-cart fa-4x text-muted mb-6 d-block"></i>
                    <h3 class="text-muted mb-4">سبد خرید شما خالی است</h3>
                    <a href="{{ route('products.index') }}"
                       class="btn btn-custom btn-secondary btn-primary-hover">
                        مشاهده محصولات
                    </a>
                </div>
            @else
                <div class="row">

                    {{-- آیتم‌های سبد --}}
                    <div class="col-lg-8 mb-8 mb-lg-0">
                        <table class="table table-bordered align-middle">
                            <thead style="background:#00225a;color:white">
                            <tr>
                                <th>محصول</th>
                                <th>قیمت</th>
                                <th>وضعیت</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cart->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item->product->thumb_url }}"
                                                 style="width:70px;height:70px;object-fit:cover"
                                                 alt="{{ $item->product->getTranslation('name', app()->getLocale()) }}">
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('products.show', $item->product->getTranslation('slug', app()->getLocale())) }}"
                                                       class="text-dark">
                                                        {{ $item->product->getTranslation('name', app()->getLocale()) }}
                                                    </a>
                                                </h6>
                                                @if($item->product->sku)
                                                    <small class="text-muted">کد: {{ $item->product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($item->price) }}</strong>
                                        <small class="text-muted d-block">{{ $item->currency }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">رزرو شده</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item->product) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-0">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('products.index') }}"
                               class="btn btn-outline-secondary rounded-0">
                                <i class="fa fa-arrow-right me-1"></i> ادامه خرید
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger rounded-0"
                                        onclick="return confirm('سبد خرید پاک شود؟')">
                                    <i class="fa fa-trash me-1"></i> پاک کردن سبد
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- خلاصه --}}
                    <div class="col-lg-4">
                        <div class="sidebar-single-item p-6" data-bg-color="#f4f8ff"
                             style="background:#f4f8ff">
                            <h4 class="mb-6">خلاصه سفارش</h4>

                            <table class="table table-sm mb-4">
                                <tr>
                                    <td>جمع جزء:</td>
                                    <td class="text-end fw-bold">{{ number_format($cart->subtotal) }}</td>
                                </tr>
                                @if($cart->discount_amount > 0)
                                    <tr class="text-success">
                                        <td>تخفیف:</td>
                                        <td class="text-end">- {{ number_format($cart->discount_amount) }}</td>
                                    </tr>
                                @endif
                                <tr class="fw-bold" style="font-size:18px">
                                    <td>جمع کل:</td>
                                    <td class="text-end text-primary">{{ number_format($cart->total) }}</td>
                                </tr>
                            </table>

                            {{-- کد تخفیف --}}
                            <form action="{{ route('cart.coupon') }}" method="POST" class="mb-4">
                                @csrf
                                <div class="form-field d-flex">
                                    <input class="input-field" type="text" name="code"
                                           value="{{ $cart->coupon_code }}"
                                           placeholder="کد تخفیف">
                                    <button type="submit"
                                            class="btn btn-secondary btn-primary-hover rounded-0">
                                        اعمال
                                    </button>
                                </div>
                            </form>

                            @auth
                                <a href="{{ route('checkout.index') }}"
                                   class="btn btn-custom btn-secondary btn-primary-hover w-100">
                                    <i class="fa fa-lock me-1"></i> ادامه و پرداخت
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="btn btn-custom btn-secondary btn-primary-hover w-100">
                                    <i class="fa fa-sign-in me-1"></i> ورود برای پرداخت
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
