@extends('front.layouts.app')
@section('title', 'پرداخت سفارش')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'خرید',
        'title'    => 'پرداخت',
    ])

    <div class="py-140">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {{-- اطلاعات سفارش --}}
                    <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">شماره سفارش: <strong>{{ $order->order_number }}</strong></h5>
                                <p class="mb-0 text-muted">{{ $order->customer_name }}</p>
                            </div>
                            <div class="text-end">
                                <h4 class="text-primary mb-0">{{ number_format($order->total) }}</h4>
                                <small class="text-muted">{{ $order->currency }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- پرداخت آنلاین --}}
                    @if($order->payment_type === 'online')
                        <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                            <h4 class="mb-4">
                                <i class="fa fa-credit-card me-2 text-primary"></i>
                                پرداخت آنلاین
                            </h4>
                            <p class="text-muted mb-6">
                                با کلیک روی دکمه زیر به درگاه بانکی امن منتقل می‌شوید.
                            </p>
                            <form action="{{ route('payment.online', $order) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="btn btn-custom btn-secondary btn-primary-hover w-100"
                                        style="font-size:18px;padding:15px">
                                    <i class="fa fa-lock me-2"></i>
                                    پرداخت امن — {{ number_format($order->total) }} {{ $order->currency }}
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- فیش بانکی --}}
                    @if($order->payment_type === 'receipt')
                        <div class="sidebar-single-item p-6" style="background:#f4f8ff">
                            <h4 class="mb-4">
                                <i class="fa fa-university me-2 text-primary"></i>
                                آپلود فیش بانکی
                            </h4>

                            {{-- اطلاعات حساب --}}
                            @if(\App\Models\Setting::get('payment_receipt_bank_name'))
                                <div class="p-4 mb-6" style="background:white;border-right:4px solid #ff5e13">
                                    <h5 class="mb-3 text-primary">اطلاعات حساب بانکی</h5>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold" width="40%">نام بانک:</td>
                                            <td>{{ \App\Models\Setting::get('payment_receipt_bank_name') }}</td>
                                        </tr>
                                        @if(\App\Models\Setting::get('payment_receipt_account_number'))
                                            <tr>
                                                <td class="fw-bold">شماره حساب:</td>
                                                <td class="font-monospace">
                                                    {{ \App\Models\Setting::get('payment_receipt_account_number') }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if(\App\Models\Setting::get('payment_receipt_iban'))
                                            <tr>
                                                <td class="fw-bold">IBAN:</td>
                                                <td class="font-monospace">
                                                    {{ \App\Models\Setting::get('payment_receipt_iban') }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if(\App\Models\Setting::get('payment_receipt_swift'))
                                            <tr>
                                                <td class="fw-bold">SWIFT:</td>
                                                <td class="font-monospace">
                                                    {{ \App\Models\Setting::get('payment_receipt_swift') }}
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                    @if(\App\Models\Setting::get('payment_receipt_instructions'))
                                        <p class="mt-3 mb-0 text-muted" style="font-size:13px">
                                            {{ \App\Models\Setting::get('payment_receipt_instructions') }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <form action="{{ route('payment.receipt', $order) }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 mb-4">
                                        <label class="form-label">نام بانک *</label>
                                        <input type="text" name="bank_name" required
                                               class="input-field w-100">
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <label class="form-label">کشور بانک *</label>
                                        <input type="text" name="bank_country" required
                                               maxlength="5" placeholder="DE, US, AE..."
                                               class="input-field w-100">
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <label class="form-label">شماره حواله / مرجع *</label>
                                        <input type="text" name="transfer_reference" required
                                               class="input-field w-100">
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <label class="form-label">تاریخ پرداخت *</label>
                                        <input type="date" name="receipt_date" required
                                               max="{{ date('Y-m-d') }}"
                                               class="input-field w-100">
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label">
                                            آپلود فیش بانکی *
                                            <small class="text-muted">(JPG, PNG, PDF — حداکثر ۵ مگابایت)</small>
                                        </label>
                                        <input type="file" name="receipt_file" required
                                               accept=".jpg,.jpeg,.png,.pdf"
                                               class="form-control rounded-0">
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label">توضیحات (اختیاری)</label>
                                        <textarea name="receipt_notes" rows="2"
                                                  class="textarea-field w-100"></textarea>
                                    </div>
                                </div>
                                <button type="submit"
                                        class="btn btn-custom btn-secondary btn-primary-hover w-100"
                                        style="font-size:16px;padding:12px">
                                    <i class="fa fa-upload me-2"></i>
                                    ارسال فیش بانکی
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
