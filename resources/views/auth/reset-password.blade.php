@extends('front.layouts.app')
@section('title', __('messages.reset_password') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.reset_password') ?? 'Reset Password',
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="sidebar-widget">
                        <div style="text-align:center;margin-bottom:1.6rem">
                            <div style="width:60px;height:60px;border-radius:50%;background:var(--brand-soft);color:var(--brand);display:flex;align-items:center;justify-content:center;margin:0 auto .9rem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <h3 class="mt-heading" style="font-size:1.2rem;margin:0">{{ __('messages.reset_password') ?? 'Reset Password' }}</h3>
                        </div>

                        <form method="POST" action="{{ route('password.store') }}" style="display:grid;gap:1rem">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div>
                                <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.email') }} *</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $request->email) }}"
                                       class="form-control @error('email') border-danger @enderror"
                                       required autofocus autocomplete="username">
                                @error('email') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                            </div>

                            <div>
                                <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.new_password') ?? 'New Password' }} *</label>
                                <input type="password" name="password"
                                       placeholder="••••••••"
                                       class="form-control @error('password') border-danger @enderror"
                                       required autocomplete="new-password">
                                @error('password') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                            </div>

                            <div>
                                <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.confirm_password') ?? 'Confirm Password' }} *</label>
                                <input type="password" name="password_confirmation"
                                       placeholder="••••••••"
                                       class="form-control"
                                       required autocomplete="new-password">
                            </div>

                            <button type="submit" class="mt-btn mt-btn-primary" style="width:100%">
                                {{ __('messages.reset_password') ?? 'Reset Password' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
