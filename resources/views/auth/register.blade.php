@extends('front.layouts.app')
@section('title', __('messages.register') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.register'),
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="sidebar-widget">
                        <div style="display:flex;align-items:center;gap:.9rem;margin-bottom:1.6rem;padding-bottom:1.2rem;border-bottom:1px solid var(--stone-100)">
                            <div style="width:44px;height:44px;border-radius:50%;background:var(--brand-soft);color:var(--brand);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <h3 class="mt-heading" style="font-size:1.2rem;margin:0">{{ __('messages.create_account') ?? 'Create Account' }}</h3>
                                <p style="font-size:.82rem;color:var(--stone-500);margin:0">{{ __('messages.register_desc') ?? 'Fill in your details to get started' }}</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('register') }}" style="display:grid;gap:1rem">
                            @csrf

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.name') }} *</label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                           placeholder="{{ __('messages.full_name_placeholder') ?? 'Full name' }}"
                                           class="form-control @error('name') border-danger @enderror"
                                           required autofocus>
                                    @error('name') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.phone') }}</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                           placeholder="{{ __('messages.phone_placeholder') ?? '+98…' }}"
                                           class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.email') }} *</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           placeholder="{{ __('messages.email_placeholder') ?? 'your@email.com' }}"
                                           class="form-control @error('email') border-danger @enderror"
                                           required autocomplete="username">
                                    @error('email') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.company') ?? 'Company' }}</label>
                                    <input type="text" name="company" value="{{ old('company') }}"
                                           placeholder="{{ __('messages.company_placeholder') ?? 'Company name' }}"
                                           class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('admin.country') }}</label>
                                    <input type="text" name="country" value="{{ old('country') }}"
                                           maxlength="5" placeholder="IR, DE, US…"
                                           class="form-control">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.preferred_language') ?? 'Preferred Language' }}</label>
                                    <select name="locale" class="form-control">
                                        @foreach(\App\Models\Language::allActive() as $lang)
                                            <option value="{{ $lang->code }}" {{ old('locale', app()->getLocale()) === $lang->code ? 'selected' : '' }}>
                                                {{ $lang->flag }} {{ $lang->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    &nbsp;
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.password') }} *</label>
                                    <input type="password" name="password"
                                           placeholder="••••••••"
                                           class="form-control @error('password') border-danger @enderror"
                                           required autocomplete="new-password">
                                    @error('password') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.confirm_password') ?? 'Confirm Password' }} *</label>
                                    <input type="password" name="password_confirmation"
                                           placeholder="••••••••"
                                           class="form-control"
                                           required autocomplete="new-password">
                                </div>
                            </div>

                            <button type="submit" class="mt-btn mt-btn-primary" style="width:100%;margin-top:.4rem">
                                {{ __('messages.create_account') ?? 'Create Account' }}
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </button>

                            <p style="text-align:center;font-size:.85rem;color:var(--stone-500);margin:0">
                                {{ __('messages.have_account') ?? 'Already have an account?' }}
                                <a href="{{ route('login') }}" style="color:var(--brand);font-weight:700">{{ __('messages.login') }}</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
