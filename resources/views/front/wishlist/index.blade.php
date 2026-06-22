@extends('front.layouts.app')
@section('title', __('messages.wishlist') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.wishlist'),
    ])

    @include('front.components.flash')

    <div class="mt-section">
        <div class="mt-container">
            @if($wishlists->count())
                <div class="row">
                    @foreach($wishlists as $wishlist)
                        @if($wishlist->product)
                            @include('front.components.product-card', ['product' => $wishlist->product])
                        @endif
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:4rem 1rem">
                    <div style="width:80px;height:80px;border-radius:50%;background:var(--stone-100);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:var(--stone-400)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="36" height="36"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </div>
                    <h3 class="mt-heading" style="font-size:1.3rem;margin-bottom:.6rem">{{ __('messages.empty_wishlist') ?? 'Your wishlist is empty' }}</h3>
                    <p style="color:var(--stone-500);margin-bottom:1.6rem">{{ __('messages.empty_wishlist_desc') ?? '' }}</p>
                    <a href="{{ route('products.index') }}" class="mt-btn mt-btn-primary">{{ __('messages.all_products') }}</a>
                </div>
            @endif
        </div>
    </div>

@endsection
