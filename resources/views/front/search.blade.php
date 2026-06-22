@extends('front.layouts.app')
@section('title', __('messages.search') . ': ' . $query . ' — ' . \App\Models\Setting::get('site_name'))
@php $locale = app()->getLocale(); @endphp

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.search'),
        'title'    => $query ? (__('messages.search_results_for') ?? 'Results for') . ' "' . $query . '"' : __('messages.search'),
    ])

    <div class="mt-section">
        <div class="mt-container">

            {{-- Search box --}}
            <div class="mt-finder" style="max-width:640px;margin:0 auto 2.6rem">
                <form action="{{ route('search') }}" method="GET" style="display:flex;flex:1;gap:.4rem">
                    <input type="search" name="q" value="{{ $query }}" placeholder="{{ __('messages.search_placeholder') }}" style="flex:1;border:0;outline:0;background:transparent;padding:.85rem 1.1rem;font-size:.95rem;color:var(--stone-900);font-family:inherit">
                    <button type="submit" style="border:0;cursor:pointer;border-radius:var(--radius);padding:0 1.5rem;background:linear-gradient(135deg,var(--brand),var(--brand-2));color:#fff;font-weight:700;display:inline-flex;align-items:center;gap:.5rem">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        {{ __('messages.search') }}
                    </button>
                </form>
            </div>

            @if(strlen($query) < 2)
                <div style="text-align:center;padding:3rem">
                    <p style="color:var(--stone-500)">{{ __('messages.search_min_chars') ?? 'Enter at least 2 characters.' }}</p>
                </div>
            @elseif($products->isEmpty() && $posts->isEmpty())
                <div style="text-align:center;padding:3rem">
                    <div style="width:72px;height:72px;border-radius:50%;background:var(--stone-100);display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;color:var(--stone-400)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="32" height="32"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                    <h4 class="mt-heading" style="font-size:1.2rem;margin-bottom:.5rem">{{ __('messages.no_results') ?? 'No results found' }}</h4>
                    <p style="color:var(--stone-500);margin-bottom:1.4rem">{{ __('messages.try_other_keyword') ?? 'Try a different keyword.' }}</p>
                    <a href="{{ route('products.index') }}" class="mt-btn mt-btn-outline">{{ __('messages.all_products') }}</a>
                </div>
            @else
                @if($products->count())
                    <div style="margin-bottom:2.6rem">
                        <div class="mt-section-head" style="margin-bottom:1.4rem">
                            <h3 class="mt-heading" style="font-size:1.25rem">{{ __('messages.products') }} <span style="font-size:.85rem;font-weight:500;color:var(--stone-500)">({{ $products->count() }})</span></h3>
                            @if($products->count() >= 12)
                                <a href="{{ route('products.index', ['q' => $query]) }}" class="mt-btn mt-btn-outline mt-btn-sm">{{ __('messages.view_all') }}</a>
                            @endif
                        </div>
                        <div class="row">
                            @foreach($products as $product)
                                @include('front.components.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($posts->count())
                    <div>
                        <h3 class="mt-heading" style="font-size:1.25rem;margin-bottom:1.4rem">{{ __('messages.news') }} <span style="font-size:.85rem;font-weight:500;color:var(--stone-500)">({{ $posts->count() }})</span></h3>
                        <div class="row g-4">
                            @foreach($posts as $post)
                                <div class="col-md-6 col-lg-4">
                                    @include('front.components.post-card', ['post' => $post])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection
