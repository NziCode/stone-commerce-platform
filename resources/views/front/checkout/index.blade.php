@extends('front.layouts.app')
@section('title', 'تسویه حساب')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'خرید',
        'title'    => 'تسویه حساب',
    ])

    <div class="py-140">
        <div class="container">
            <div class="row">

                {{-- فرم --}}
                <div class="col-lg-8 mb-8 mb-lg-0">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        {{-- اطلاعات خریدار --}}
                        <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                            <h4 class="mb-6">اطلاعات خریدار</h4>
                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">نام و نام خانوادگی *</label>
                                    <input type="text" name="customer_name"
                                           value="{{ old('customer_name', auth()->user()->name) }}"
                                           class="input-field w-100 @error('customer_name') border-danger @enderror"
                                           required>
                                    @error('customer_name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">ایمیل *</label>
                                    <input type="email" name="customer_email"
                                           value="{{ old('customer_email', auth()->user()->email) }}"
                                           class="input-field w-100 @error('customer_email') border-danger @enderror"
                                           required>
                                    @error('customer_email')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">تلفن</label>
                                    <input type="text" name="customer_phone"
                                           value="{{ old('customer_phone', auth()->user()->phone) }}"
                                           class="input-field w-100">
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">شرکت</label>
                                    <input type="text" name="customer_company"
                                           value="{{ old('customer_company', auth()->user()->company) }}"
                                           class="input-field w-100">
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">کشور</label>
                                    <input type="text" name="customer_country"
                                           value="{{ old('customer_country', auth()->user()->country) }}"
                                           maxlength="5" placeholder="IR, DE, US..."
                                           class="input-field w-100">
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">کد پستی</label>
                                    <input type="text" name="customer_postal_code"
                                           value="{{ old('customer_postal_code') }}"
                                           class="input-field w-100">
                                </div>
                                <div class="col-12 mb-4">
                                    <label class="form-label">آدرس</label>
                                    <textarea name="customer_address" rows="3"
                                              class="textarea-field w-100">{{ old('customer_address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- روش پرداخت --}}
                        <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                            <h4 class="mb-6">روش پرداخت</h4>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="d-flex align-items-start gap-3 p-4 border"
                                           style="cursor:pointer;background:white">
                                        <input type="radio" name="payment_type" value="online"
                                               checked class="mt-1">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fa fa-credit-card me-2 text-primary"></i>
                                                پرداخت آنلاین
                                            </h6>
                                            <small class="text-muted">
                                                پرداخت امن از طریق درگاه بانکی ایرانی
                                            </small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="d-flex align-items-start gap-3 p-4 border"
                                           style="cursor:pointer;background:white">
                                        <input type="radio" name="payment_type" value="receipt"
                                               class="mt-1">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fa fa-university me-2 text-primary"></i>
                                                فیش بانکی بین‌المللی
                                            </h6>
                                            <small class="text-muted">
                                                حواله بانکی یا صرافی برای خریداران خارجی
                                            </small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- یادداشت --}}
                        <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                            <h4 class="mb-4">یادداشت (اختیاری)</h4>
                            <textarea name="customer_notes" rows="3"
                                      placeholder="هرگونه توضیح اضافه..."
                                      class="textarea-field w-100"></textarea>
                        </div>

                        <button type="submit"
                                class="btn btn-custom btn-secondary btn-primary-hover w-100"
                                style="font-size:18px;padding:15px">
                            ثبت سفارش و ادامه به پرداخت
                            <i class="fa fa-arrow-left ms-2"></i>
                        </button>
                    </form>
                </div>

                {{-- خلاصه --}}
                <div class="col-lg-4">
                    <div class="sidebar-single-item p-6" style="background:#f4f8ff">
                        <h4 class="mb-6">سفارش شما</h4>
                        @foreach($cart->items as $item)
                            <div class="d-flex gap-3 mb-4 pb-4 border-bottom">
                                <img src="{{ $item->product->thumb_url }}"
                                     style="width:60px;height:60px;object-fit:cover;flex-shrink:0">
                                <div class="flex-1">
                                    <p class="mb-1 fw-medium" style="font-size:14px">
                                        {{ $item->product->getTranslation('name', app()->getLocale()) }}
                                    </p>
                                    <p class="mb-0 text-primary fw-bold">
                                        {{ number_format($item->price) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        <table class="table table-sm mt-4">
                            <tr>
                                <td>جمع جزء:</td>
                                <td class="text-end">{{ number_format($cart->subtotal) }}</td>
                            </tr>
                            @if($cart->discount_amount > 0)
                                <tr class="text-success">
                                    <td>تخفیف:</td>
                                    <td class="text-end">- {{ number_format($cart->discount_amount) }}</td>
                                </tr>
                            @endif
                            <tr class="fw-bold">
                                <td>جمع کل:</td>
                                <td class="text-end text-primary" style="font-size:18px">
                                    {{ number_format($cart->total) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
