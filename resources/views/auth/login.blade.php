@extends('front.layouts.app')
@section('title', __('messages.login') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.login_register') ?? 'Sign In / Register',
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row g-5">

                {{-- Login --}}
                <div class="col-lg-6">
                    <div class="sidebar-widget" style="height:100%">
                        <div style="display:flex;align-items:center;gap:.9rem;margin-bottom:1.6rem;padding-bottom:1.2rem;border-bottom:1px solid var(--stone-100)">
                            <div style="width:44px;height:44px;border-radius:50%;background:var(--brand-soft);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                            </div>
                            <div>
                                <h3 class="mt-heading" style="font-size:1.2rem;margin:0">{{ __('messages.login') }}</h3>
                                <p style="font-size:.82rem;color:var(--stone-500);margin:0">{{ __('messages.login_desc') ?? 'Sign in to your account' }}</p>
                            </div>
                        </div>

                        @include('front.components.flash')

                        <form method="POST" action="{{ route('login') }}" style="display:grid;gap:1rem">
                            @csrf

                            <div>
                                <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.email') }} *</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       placeholder="{{ __('messages.email_placeholder') ?? 'your@email.com' }}"
                                       class="form-control @error('email') border-danger @enderror"
                                       required autofocus autocomplete="username">
                                @error('email') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                            </div>

                            <div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink);margin:0">{{ __('messages.password') }} *</label>
                                    @if(Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" style="font-size:.78rem;color:var(--brand)">{{ __('messages.forgot_password') ?? 'Forgot password?' }}</a>
                                    @endif
                                </div>
                                <input type="password" name="password"
                                       placeholder="••••••••"
                                       class="form-control @error('password') border-danger @enderror"
                                       required autocomplete="current-password">
                                @error('password') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                            </div>

                            <label style="display:flex;align-items:center;gap:.6rem;font-size:.84rem;color:var(--stone-700);cursor:pointer">
                                <input type="checkbox" name="remember" style="width:16px;height:16px;accent-color:var(--brand)">
                                {{ __('messages.remember_me') ?? 'Remember me' }}
                            </label>

                            <button type="submit" class="mt-btn mt-btn-primary" style="width:100%">
                                {{ __('messages.login') }}
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                            </button>

                            <p style="text-align:center;font-size:.85rem;color:var(--stone-500);margin:0">
                                {{ __('messages.no_account') ?? "Don't have an account?" }}
                                <a href="{{ route('register') }}" style="color:var(--brand);font-weight:700">{{ __('messages.register') }}</a>
                            </p>
                        </form>
                    </div>
                </div>

                {{-- Register preview --}}
                <div class="col-lg-6">
                    <div class="mt-band" style="height:100%;display:flex;flex-direction:column;justify-content:center;padding:2.4rem">
                        <span class="mt-eyebrow" style="color:var(--brand-2);margin-bottom:.8rem">{{ __('messages.new_customer') ?? 'New Customer' }}</span>
                        <h3 class="mt-heading" style="color:#fff;font-size:1.4rem;margin-bottom:.8rem">{{ __('messages.register_title') ?? 'Create Your Account' }}</h3>
                        <p style="color:rgba(255,255,255,.75);line-height:1.85;margin-bottom:1.6rem;font-size:.92rem">{{ __('messages.register_desc') ?? 'Register to track your orders, save favourites, and get faster checkout.' }}</p>
                        <ul style="list-style:none;margin:0 0 2rem;padding:0;display:grid;gap:.7rem">
                            @foreach([
                                __('messages.benefit_track') ?? 'Track your orders in real time',
                                __('messages.benefit_wishlist') ?? 'Save products to your wishlist',
                                __('messages.benefit_fast') ?? 'Faster checkout every time',
                                __('messages.benefit_history') ?? 'Full order history & invoices',
                            ] as $benefit)
                                <li style="display:flex;align-items:center;gap:.7rem;color:rgba(255,255,255,.85);font-size:.88rem">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16" style="color:var(--brand-2);flex-shrink:0"><path d="M20 6 9 17l-5-5"/></svg>
                                    {{ $benefit }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('register') }}" class="mt-btn mt-btn-ghost-white" style="justify-self:start;width:fit-content">
                            {{ __('messages.create_account') ?? 'Create Account' }}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
