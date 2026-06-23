@extends('front.layouts.app')
@section('title', __('messages.newsletter_subscribed_title') ?? 'Subscribed!')

@section('content')

    @include('front.components.breadcrumb', [
        'title' => __('messages.newsletter_subscribed_title') ?? 'You\'re Subscribed!',
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div style="max-width:520px;margin:0 auto;text-align:center;padding:2rem 1rem">

                <div style="width:88px;height:88px;border-radius:50%;background:linear-gradient(135deg,var(--brand),var(--brand-2));display:flex;align-items:center;justify-content:center;margin:0 auto 1.6rem;box-shadow:0 16px 32px -12px rgba(255,90,31,.5)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" width="40" height="40">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 6-10 7L2 6"/>
                    </svg>
                </div>

                <h2 class="mt-heading" style="font-size:1.6rem;margin-bottom:.7rem">
                    {{ __('messages.newsletter_subscribed_title') ?? "You're Subscribed!" }}
                </h2>

                <p style="color:var(--stone-500);line-height:1.85;margin:0 0 .8rem;font-size:.95rem">
                    {{ __('messages.newsletter_subscribed_desc') ?? 'You will receive the latest news, products, and exhibitions from us.' }}
                </p>

                <p style="color:var(--stone-500);font-size:.85rem;margin:0 0 2rem">
                    {{ __('messages.newsletter_unsubscribe_hint') ?? 'You can unsubscribe at any time using the link in our emails.' }}
                </p>

                <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap">
                    <a href="{{ route('home') }}" class="mt-btn mt-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <path d="M9 22V12h6v10"/>
                        </svg>
                        {{ __('messages.back_to_home') }}
                    </a>
                    <a href="{{ route('products.index') }}" class="mt-btn mt-btn-outline">
                        {{ __('messages.all_products') }}
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection
