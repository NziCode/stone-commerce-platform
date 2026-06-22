@extends('front.layouts.app')
@section('title', __('messages.confirm_password') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.confirm_identity') ?? 'Confirm Identity',
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="sidebar-widget">
                        <div style="text-align:center;margin-bottom:1.6rem">
                            <div style="width:60px;height:60px;border-radius:50%;background:var(--stone-100);color:var(--ink);display:flex;align-items:center;justify-content:center;margin:0 auto .9rem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <h3 class="mt-heading" style="font-size:1.2rem;margin-bottom:.4rem">{{ __('messages.confirm_identity') ?? 'Confirm Identity' }}</h3>
                            <p style="font-size:.84rem;color:var(--stone-500);margin:0">{{ __('messages.confirm_password_desc') ?? 'Please confirm your password to continue.' }}</p>
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}" style="display:grid;gap:1rem">
                            @csrf
                            <div>
                                <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.password') }} *</label>
                                <input type="password" name="password"
                                       placeholder="••••••••"
                                       class="form-control @error('password') border-danger @enderror"
                                       required autocomplete="current-password">
                                @error('password') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                            </div>

                            <button type="submit" class="mt-btn mt-btn-primary" style="width:100%">
                                {{ __('messages.confirm') ?? 'Confirm' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
