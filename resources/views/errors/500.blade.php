@extends('errors.layout')

@section('title', __('messages.error_500_title') ?? 'Server Error')

@section('content')

    <div class="err-icon" style="background:#fdecea;color:#e0473a">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 8v4M12 16h.01" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </div>

    <span class="err-code" style="background:linear-gradient(135deg,#e0473a,#ff5a1f);-webkit-background-clip:text;background-clip:text">500</span>

    <h1 class="err-title">{{ __('messages.error_500_title') ?? 'Server Error' }}</h1>

    <p class="err-desc">{{ __('messages.error_500_desc') ?? 'Something went wrong on our side. We\'ve been notified and are working on a fix.' }}</p>

    <div class="err-actions">
        <a href="{{ url('/') }}" class="err-btn err-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <path d="M9 22V12h6v10"/>
            </svg>
            {{ __('messages.back_to_home') ?? 'Back to Home' }}
        </a>
        <a href="javascript:window.location.reload()" class="err-btn err-btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M23 4v6h-6M1 20v-6h6"/>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
            </svg>
            {{ __('messages.try_again') ?? 'Try Again' }}
        </a>
        <a href="{{ route('contact') }}" class="err-btn err-btn-outline">
            {{ __('messages.report_problem') ?? 'Report Problem' }}
        </a>
    </div>

@endsection
