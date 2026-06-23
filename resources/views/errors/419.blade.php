@extends('errors.layout')
@section('title', __('messages.error_419_title') ?? 'Session Expired')
@section('content')
    <div class="err-icon" style="background:#fff8e1;color:#e0a400">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
        </svg>
    </div>
    <span class="err-code" style="background:linear-gradient(135deg,#e0a400,#f5c842);-webkit-background-clip:text;background-clip:text">419</span>
    <h1 class="err-title">{{ __('messages.error_419_title') ?? 'Session Expired' }}</h1>
    <p class="err-desc">{{ __('messages.error_419_desc') ?? 'Your session has expired. Please refresh the page and try again.' }}</p>
    <div class="err-actions">
        <a href="javascript:window.location.reload()" class="err-btn err-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
            {{ __('messages.try_again') ?? 'Refresh Page' }}
        </a>
        <a href="{{ url('/') }}" class="err-btn err-btn-outline">{{ __('messages.back_to_home') ?? 'Back to Home' }}</a>
    </div>
@endsection
