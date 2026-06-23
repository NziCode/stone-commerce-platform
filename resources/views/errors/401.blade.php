@extends('errors.layout')
@section('title', __('messages.error_401_title') ?? 'Unauthorized')
@section('content')
    <div class="err-icon" style="background:var(--stone-100);color:var(--ink-2)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
    </div>
    <span class="err-code">401</span>
    <h1 class="err-title">{{ __('messages.error_401_title') ?? 'Unauthorized' }}</h1>
    <p class="err-desc">{{ __('messages.error_401_desc') ?? 'Please log in to access this page.' }}</p>
    <div class="err-actions">
        <a href="{{ route('login') }}" class="err-btn err-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
            {{ __('messages.login') }}
        </a>
        <a href="{{ url('/') }}" class="err-btn err-btn-outline">{{ __('messages.back_to_home') ?? 'Back to Home' }}</a>
    </div>
@endsection
