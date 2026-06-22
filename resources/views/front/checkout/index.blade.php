@extends('front.layouts.app')
@section('title', __('messages.checkout') . ' — ' . \App\Models\Setting::get('site_name'))
@php $locale = app()->getLocale(); @endphp

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.shop'),
        'title'    => __('messages.checkout'),
    ])

    @include('front.components.flash')

    <div class="mt-section">
        <div class="mt-container">
            <div class="row">

                <div class="col-lg-8 mb-8 mb-lg-0">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        {{-- Buyer Info --}}
                        <div class="sidebar-widget" style="margin-bottom:1.4rem">
                            <h4 class="sidebar-title">{{ __('messages.buyer_info') ?? 'Buyer Info' }}</h4>
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.name') }} *</label>
                                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" class="form-control @error('customer_name') border-danger @enderror" required>
                                    @error('customer_name') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.email') }} *</label>
                                    <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email) }}" class="form-control @error('customer_email') border-danger @enderror" required>
                                    @error('customer_email') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.phone') }}</label>
                                    <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone) }}" class="form-control">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.company') ?? 'Company' }}</label>
                                    <input type="text" name="customer_company" value="{{ old('customer_company', auth()->user()->company) }}" class="form-control">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('admin.country') }}</label>
                                    <input type="text" name="customer_country" value="{{ old('customer_country', auth()->user()->country) }}" maxlength="5" placeholder="IR, DE, US…" class="form-control">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.postal_code') ?? 'Postal Code' }}</label>
                                    <input type="text" name="customer_postal_code" value="{{ old('customer_postal_code') }}" class="form-control">
                                </div>
                                <div class="col-12 mb-1">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.address') }}</label>
                                    <textarea name="customer_address" rows="2" class="form-control">{{ old('customer_address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="sidebar-widget" style="margin-bottom:1.4rem">
                            <h4 class="sidebar-title">{{ __('messages.payment_method') }}</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label style="display:flex;align-items:flex-start;gap:.9rem;padding:1.1rem 1.2rem;background:var(--stone-50);border:2px solid var(--stone-200);border-radius:var(--radius);cursor:pointer;transition:.2s" id="lbl_online">
                                        <input type="radio" name="payment_type" value="online" checked style="margin-top:3px" onchange="document.getElementById('lbl_online').style.borderColor='var(--brand)';document.getElementById('lbl_receipt').style.borderColor='var(--stone-200)'">
                                        <div>
                                            <strong style="display:block;color:var(--ink);margin-bottom:.2rem">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" style="margin-inline-end:.35rem;color:var(--brand)"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                                                {{ __('messages.online_payment') ?? 'Online Payment' }}
                                            </strong>
                                            <span style="font-size:.78rem;color:var(--stone-500)">{{ __('messages.online_payment_desc') ?? 'Secure payment via Iranian gateway' }}</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label style="display:flex;align-items:flex-start;gap:.9rem;padding:1.1rem 1.2rem;background:var(--stone-50);border:2px solid var(--stone-200);border-radius:var(--radius);cursor:pointer;transition:.2s" id="lbl_receipt">
                                        <input type="radio" name="payment_type" value="receipt" style="margin-top:3px" onchange="document.getElementById('lbl_receipt').style.borderColor='var(--brand)';document.getElementById('lbl_online').style.borderColor='var(--stone-200)'">
                                        <div>
                                            <strong style="display:block;color:var(--ink);margin-bottom:.2rem">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" style="margin-inline-end:.35rem;color:var(--brand)"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                                                {{ __('messages.receipt_payment') ?? 'Bank Transfer / Receipt' }}
                                            </strong>
                                            <span style="font-size:.78rem;color:var(--stone-500)">{{ __('messages.receipt_payment_desc') ?? 'Wire transfer for international buyers' }}</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="sidebar-widget" style="margin-bottom:1.4rem">
                            <h4 class="sidebar-title">{{ __('messages.order_notes') ?? 'Notes' }} <span style="font-weight:400;font-size:.82rem;color:var(--stone-500)">({{ __('messages.optional') ?? 'optional' }})</span></h4>
                            <textarea name="customer_notes" rows="3" placeholder="{{ __('messages.order_notes_placeholder') ?? 'Any additional notes…' }}" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="mt-btn mt-btn-primary" style="width:100%;font-size:1rem;padding:1.1rem">
                            {{ __('messages.place_order') ?? 'Place Order & Continue to Payment' }}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </button>
                    </form>
                </div>

                {{-- Order summary --}}
                <div class="col-lg-4">
                    <div class="sidebar-widget">
                        <h4 class="sidebar-title">{{ __('messages.your_order') ?? 'Your Order' }}</h4>
                        @foreach($cart->items as $item)
                            <div style="display:flex;gap:.8rem;padding-bottom:.9rem;margin-bottom:.9rem;border-bottom:1px solid var(--stone-100);align-items:center">
                                <div style="width:52px;height:52px;flex-shrink:0;border-radius:10px;overflow:hidden;background:var(--stone-50)">
                                    <img src="{{ $item->product->thumb_url }}" style="width:100%;height:100%;object-fit:cover" alt="">
                                </div>
                                <div style="flex:1">
                                    <p style="font-size:.84rem;font-weight:600;color:var(--ink);margin:0 0 .15rem;line-height:1.4">{{ $item->product->getTranslation('name', $locale) }}</p>
                                    <span style="font-size:.82rem;font-weight:700;color:var(--brand)">{{ number_format($item->price) }} {{ $item->currency }}</span>
                                </div>
                            </div>
                        @endforeach

                        <div style="display:grid;gap:.5rem;padding-top:.4rem">
                            <div style="display:flex;justify-content:space-between;font-size:.88rem">
                                <span style="color:var(--stone-500)">{{ __('messages.subtotal') ?? 'Subtotal' }}</span>
                                <span style="font-weight:600">{{ number_format($cart->subtotal) }}</span>
                            </div>
                            @if($cart->discount_amount > 0)
                                <div style="display:flex;justify-content:space-between;font-size:.88rem;color:var(--ok)">
                                    <span>{{ __('messages.discount') ?? 'Discount' }}</span>
                                    <span style="font-weight:600">— {{ number_format($cart->discount_amount) }}</span>
                                </div>
                            @endif
                            <div style="display:flex;justify-content:space-between;padding-top:.6rem;border-top:1px solid var(--stone-100)">
                                <span style="font-weight:700;color:var(--ink)">{{ __('messages.total') }}</span>
                                <span style="font-weight:800;font-size:1.1rem;color:var(--brand)">{{ number_format($cart->total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
