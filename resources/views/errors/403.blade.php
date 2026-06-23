@extends('errors.layout')
@section('title', __('messages.error_403_title') ?? 'Forbidden')
@section('content')
    <div class="err-icon" style="background:#fdecea;color:#e0473a">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10"/>
            <path d="M4.93 4.93l14.14 14.14"/>
        </svg>
    </div>
    <span class="err-code">403</span>
    <h1 class="err-title">{{ __('messages.error_403_title') ?? 'Access Denied' }}</h1>
    <p class="err-desc">{{ __('messages.error_403_desc') ?? 'You do not have permission to access this resource.' }}</p>
    <div class="err-actions">
        <a href="{{ url('/') }}" class="err-btn err-btn-primary">{{ __('messages.back_to_home') ?? 'Back to Home' }}</a>
        <a href="{{ route('contact') }}" class="err-btn err-btn-outline">{{ __('messages.contact') }}</a>
    </div>
@endsection
