@extends('front.layouts.app')
@section('title', __('messages.newsletter_unsubscribe') ?? 'Unsubscribe')

@section('content')

    @include('front.components.breadcrumb', [
        'title' => __('messages.newsletter_unsubscribe') ?? 'Unsubscribe from Newsletter',
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div style="max-width:480px;margin:0 auto;text-align:center;padding:2rem 1rem">
                <div style="width:80px;height:80px;border-radius:50%;background:#e9f9ef;color:#1f9d55;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="36" height="36"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <h2 class="mt-heading" style="font-size:1.4rem;margin-bottom:.6rem">{{ __('messages.newsletter_unsubscribed_title') ?? 'You have been unsubscribed' }}</h2>
                <p style="color:var(--stone-500);margin-bottom:2rem;line-height:1.8">{{ __('messages.newsletter_unsubscribed_desc') ?? 'You have successfully unsubscribed from our newsletter.' }}</p>
                <a href="{{ route('home') }}" class="mt-btn mt-btn-primary">{{ __('messages.back_to_home') ?? 'Back to Home' }}</a>
            </div>
        </div>
    </div>

@endsection
