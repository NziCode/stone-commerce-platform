@extends('errors.layout')

@section('title', __('messages.error_404_title') ?? 'Page Not Found')

@section('content')

    <div class="err-icon" style="background:var(--stone-100);color:var(--stone-500)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.3-4.3"/>
            <path d="M8 11h6M11 8v6" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
    </div>

    <span class="err-code">404</span>

    <h1 class="err-title">{{ __('messages.error_404_title') ?? 'Page Not Found' }}</h1>

    <p class="err-desc">{{ __('messages.error_404_desc') ?? 'The page you\'re looking for doesn\'t exist, has been moved, or the link is incorrect.' }}</p>

    <div class="err-actions">
        <a href="{{ url('/') }}" class="err-btn err-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <path d="M9 22V12h6v10"/>
            </svg>
            {{ __('messages.back_to_home') ?? 'Back to Home' }}
        </a>
        <a href="{{ route('products.index') }}" class="err-btn err-btn-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            {{ __('messages.all_products') }}
        </a>
        <a href="{{ route('contact') }}" class="err-btn err-btn-outline">
            {{ __('messages.contact') }}
        </a>
    </div>

@endsection
