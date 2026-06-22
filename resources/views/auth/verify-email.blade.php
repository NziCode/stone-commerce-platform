@extends('front.layouts.app')
@section('title', __('messages.verify_email') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.verify_email') ?? 'Verify Email',
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="sidebar-widget" style="text-align:center">
                        <div style="width:70px;height:70px;border-radius:50%;background:var(--stone-100);color:var(--ink-2);display:flex;align-items:center;justify-content:center;margin:0 auto 1.3rem">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="30" height="30"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
                        </div>
                        <h3 class="mt-heading" style="font-size:1.2rem;margin-bottom:.6rem">{{ __('messages.check_your_email') ?? 'Check Your Email' }}</h3>
                        <p style="color:var(--stone-500);margin-bottom:1.6rem;line-height:1.85;font-size:.9rem">{{ __('messages.verify_email_desc') ?? 'A verification link has been sent to your email address. Please check your inbox.' }}</p>

                        @if(session('status') == 'verification-link-sent')
                            <div class="alert alert-success" style="margin-bottom:1.2rem">
                                {{ __('messages.verification_link_sent') ?? 'A new verification link has been sent.' }}
                            </div>
                        @endif

                        <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="mt-btn mt-btn-primary">{{ __('messages.resend_link') ?? 'Resend Link' }}</button>
                            </form>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="mt-btn mt-btn-outline">{{ __('messages.logout') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
