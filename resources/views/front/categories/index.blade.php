@extends('front.layouts.app')

@section('title', __('messages.categories') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => \App\Models\Setting::get('site_name'),
        'title'    => __('messages.categories'),
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row g-4">
                @forelse($categories as $cat)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('categories.show', $cat->getTranslation('slug', app()->getLocale())) }}" class="mt-cat" style="padding:1.8rem 1.2rem;height:100%">
                            <span class="mt-cat-ico" style="width:72px;height:72px;font-size:1.5rem">
                                @if($cat->hasMedia('image'))
                                    <img src="{{ $cat->thumb_url }}" alt="{{ $cat->getTranslation('name', app()->getLocale()) }}">
                                @else
                                    {{ mb_substr($cat->getTranslation('name', app()->getLocale()), 0, 1) }}
                                @endif
                            </span>
                            <strong style="font-size:1rem">{{ $cat->getTranslation('name', app()->getLocale()) }}</strong>
                            <span>{{ $cat->products()->where('is_active', true)->count() }} {{ __('messages.products') }}</span>
                            @if($cat->children->count())
                                <span style="display:flex;flex-wrap:wrap;gap:.35rem;justify-content:center;margin-top:.4rem;border-top:1px solid var(--stone-100);padding-top:.7rem">
                                    @foreach($cat->children->take(4) as $child)
                                        <span style="font-size:.7rem;color:var(--stone-500);background:var(--stone-50);border-radius:var(--radius-pill);padding:.2rem .55rem">
                                            {{ $child->getTranslation('name', app()->getLocale()) }}
                                        </span>
                                    @endforeach
                                </span>
                            @endif
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="no-products-found">
                            <p class="text-muted mb-0">{{ __('messages.no_products') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection
