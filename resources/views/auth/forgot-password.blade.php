@extends('front.layouts.app')
@section('title', __('messages.forgot_password') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.forgot_password') ?? 'Forgot Password',
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="sidebar-widget">
                        <div style="text-align:center;margin-bottom:1.6rem">
                            <div style="width:60px;height:60px;border-radius:50%;background:var(--brand-soft);color:var(--brand);display:flex;align-items:center;justify-content:center;margin:0 auto .9rem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
                            </div>
                            <h3 class="mt-heading" style="font-size:1.2rem;margin-bottom:.4rem">{{ __('messages.forgot_password') ?? 'Forgot Password' }}</h3>
                            <p style="font-size:.84rem;color:var(--stone-500);margin:0;line-height:1.7">{{ __('messages.forgot_password_desc') ?? 'Enter your email and we will send you a reset link.' }}</p>
                        </div>

                        @if(session('status'))
                            <div class="alert alert-success" style="margin-bottom:1.2rem">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" style="display:grid;gap:1rem">
                            @csrf
                            <div>
                                <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.email') }} *</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       placeholder="{{ __('messages.email_placeholder') ?? 'your@email.com' }}"
                                       class="form-control @error('email') border-danger @enderror"
                                       required autofocus>
                                @error('email') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                            </div>

                            <button type="submit" class="mt-btn mt-btn-primary" style="width:100%">
                                {{ __('messages.send_reset_link') ?? 'Send Reset Link' }}
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                            </button>

                            <div style="text-align:center">
                                <a href="{{ route('login') }}" style="font-size:.84rem;color:var(--stone-500);text-decoration:none;display:inline-flex;align-items:center;gap:.4rem">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                    {{ __('messages.back_to_login') ?? 'Back to Login' }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
