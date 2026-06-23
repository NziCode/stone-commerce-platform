@extends('errors.layout')
@section('title', __('messages.error_429_title') ?? 'Too Many Requests')
@section('content')
    <div class="err-icon" style="background:var(--stone-100);color:var(--stone-500)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>
        </svg>
    </div>
    <span class="err-code">429</span>
    <h1 class="err-title">{{ __('messages.error_429_title') ?? 'Too Many Requests' }}</h1>
    <p class="err-desc">{{ __('messages.error_429_desc') ?? 'You\'ve made too many requests. Please wait a moment and try again.' }}</p>
    <div class="err-actions">
        <a href="javascript:setTimeout(()=>window.location.reload(),3000)" onclick="this.textContent='Waiting...';this.style.opacity='.6'" class="err-btn err-btn-primary">{{ __('messages.try_again') ?? 'Try Again' }}</a>
        <a href="{{ url('/') }}" class="err-btn err-btn-outline">{{ __('messages.back_to_home') ?? 'Back to Home' }}</a>
    </div>
@endsection
