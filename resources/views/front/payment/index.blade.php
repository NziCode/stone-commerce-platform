@extends('front.layouts.app')
@section('title', __('messages.payment') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.shop'),
        'title'    => __('messages.payment'),
    ])

    @include('front.components.flash')

    <div class="mt-section">
        <div class="mt-container">
            <div class="row justify-content-center">
                <div class="col-lg-7">

                    {{-- Order summary --}}
                    <div class="sidebar-widget" style="margin-bottom:1.4rem">
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <div>
                                <span style="font-size:.78rem;color:var(--stone-500);font-weight:600;text-transform:uppercase;letter-spacing:.04em">{{ __('messages.order_number') ?? 'Order' }}</span>
                                <h4 style="margin:.2rem 0 .1rem;color:var(--ink)">{{ $order->order_number }}</h4>
                                <span style="font-size:.84rem;color:var(--stone-500)">{{ $order->customer_name }}</span>
                            </div>
                            <div style="text-align:end">
                                <span style="display:block;font-size:1.6rem;font-weight:800;color:var(--brand)">{{ number_format($order->total) }}</span>
                                <span style="font-size:.78rem;color:var(--stone-500)">{{ $order->currency }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Online Payment --}}
                    @if($order->payment_type === 'online')
                        <div class="sidebar-widget" style="text-align:center;padding:2.4rem">
                            <div style="width:64px;height:64px;border-radius:50%;background:var(--brand-soft);color:var(--brand);display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="28" height="28"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                            </div>
                            <h4 class="mt-heading" style="font-size:1.2rem;margin-bottom:.6rem">{{ __('messages.online_payment') ?? 'Online Payment' }}</h4>
                            <p style="color:var(--stone-500);margin-bottom:1.8rem;font-size:.9rem">{{ __('messages.redirect_gateway') ?? 'You will be redirected to a secure payment gateway.' }}</p>
                            <form action="{{ route('payment.online', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="mt-btn mt-btn-primary" style="font-size:1.05rem;padding:1rem 2rem">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    {{ __('messages.pay_secure') ?? 'Secure Pay' }} — {{ number_format($order->total) }} {{ $order->currency }}
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Receipt Upload --}}
                    @if($order->payment_type === 'receipt')
                        <div class="sidebar-widget" style="margin-bottom:1.4rem">
                            <h4 class="sidebar-title">{{ __('messages.upload_receipt') ?? 'Upload Bank Receipt' }}</h4>

                            @if(\App\Models\Setting::get('payment_receipt_bank_name'))
                                <div style="background:var(--stone-50);border-inline-start:4px solid var(--brand);border-radius:0 10px 10px 0;padding:1.1rem 1.3rem;margin-bottom:1.4rem">
                                    <h5 style="font-size:.9rem;font-weight:700;color:var(--ink);margin:0 0 .8rem">{{ __('messages.bank_info') ?? 'Bank Account Details' }}</h5>
                                    <table style="width:100%;font-size:.84rem">
                                        <tr><td style="color:var(--stone-500);padding:.25rem 0;width:40%">{{ __('messages.bank_name') ?? 'Bank' }}:</td><td style="font-weight:600;color:var(--ink)">{{ \App\Models\Setting::get('payment_receipt_bank_name') }}</td></tr>
                                        @if(\App\Models\Setting::get('payment_receipt_account_number'))
                                            <tr><td style="color:var(--stone-500);padding:.25rem 0">{{ __('messages.account_number') ?? 'Account' }}:</td><td style="font-weight:600;font-family:monospace">{{ \App\Models\Setting::get('payment_receipt_account_number') }}</td></tr>
                                        @endif
                                        @if(\App\Models\Setting::get('payment_receipt_iban'))
                                            <tr><td style="color:var(--stone-500);padding:.25rem 0">IBAN:</td><td style="font-weight:600;font-family:monospace">{{ \App\Models\Setting::get('payment_receipt_iban') }}</td></tr>
                                        @endif
                                        @if(\App\Models\Setting::get('payment_receipt_swift'))
                                            <tr><td style="color:var(--stone-500);padding:.25rem 0">SWIFT:</td><td style="font-weight:600;font-family:monospace">{{ \App\Models\Setting::get('payment_receipt_swift') }}</td></tr>
                                        @endif
                                    </table>
                                    @if(\App\Models\Setting::get('payment_receipt_instructions'))
                                        <p style="font-size:.78rem;color:var(--stone-500);margin:.8rem 0 0">{{ \App\Models\Setting::get('payment_receipt_instructions') }}</p>
                                    @endif
                                </div>
                            @endif

                            <form action="{{ route('payment.receipt', $order) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.bank_name') ?? 'Bank Name' }} *</label>
                                        <input type="text" name="bank_name" required class="form-control">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.bank_country') ?? 'Bank Country' }} *</label>
                                        <input type="text" name="bank_country" required maxlength="5" placeholder="DE, US, AE…" class="form-control">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.transfer_ref') ?? 'Transfer Reference' }} *</label>
                                        <input type="text" name="transfer_reference" required class="form-control">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.payment_date') ?? 'Payment Date' }} *</label>
                                        <input type="date" name="receipt_date" required max="{{ date('Y-m-d') }}" class="form-control">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">
                                            {{ __('messages.receipt_file') ?? 'Receipt File' }} *
                                            <span style="font-weight:400;color:var(--stone-500)">(JPG, PNG, PDF — max 5MB)</span>
                                        </label>
                                        <input type="file" name="receipt_file" required accept=".jpg,.jpeg,.png,.pdf" class="form-control">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.notes') ?? 'Notes' }} ({{ __('messages.optional') ?? 'optional' }})</label>
                                        <textarea name="receipt_notes" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="mt-btn mt-btn-primary" style="width:100%">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                                    {{ __('messages.submit_receipt') ?? 'Submit Receipt' }}
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
