@extends('errors.layout')
@section('title', __('messages.error_503_title') ?? 'Maintenance Mode')
@section('content')
    <div class="err-icon" style="background:var(--stone-100);color:var(--ink-2)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
        </svg>
    </div>
    <span class="err-code">503</span>
    <h1 class="err-title">{{ __('messages.error_503_title') ?? 'Under Maintenance' }}</h1>
    <p class="err-desc">{{ __('messages.error_503_desc') ?? 'We\'re making improvements. We\'ll be back shortly — thank you for your patience.' }}</p>
    <div class="err-actions">
        <a href="javascript:window.location.reload()" class="err-btn err-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
            {{ __('messages.try_again') ?? 'Try Again' }}
        </a>
    </div>
@endsection
