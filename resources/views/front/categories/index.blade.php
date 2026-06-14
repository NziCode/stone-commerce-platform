@extends('front.layouts.app')

@section('title', __('messages.categories') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    {{-- Breadcrumb --}}
    <div class="breadcrumb-area breadcrumb-height"
         data-bg-image="{{ asset('assets/images/breadcrumb/bg/1.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <span class="breadcrumb-sub-title">{{ \App\Models\Setting::get('site_name') }}</span>
                <h1 class="breadcrumb-title mb-1">{{ __('messages.categories') }}</h1>
                <nav aria-label="breadcrumb" class="mt-3">
                    <ol class="breadcrumb justify-content-center" style="background:none;padding:0;margin:0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" style="color:rgba(255,255,255,0.75)">
                                {{ __('messages.home') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" style="color:#fff">
                            {{ __('messages.categories') }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Categories Grid --}}
    <div class="category-area py-140">
        <div class="container">
            <div class="row">
                @forelse($categories as $cat)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-8">
                        <a href="{{ route('categories.show', $cat->getTranslation('slug', app()->getLocale())) }}"
                           class="d-block" style="text-decoration:none">
                            <div class="category-card" style="border:1px solid #eee;padding:24px;text-align:center;transition:all 0.3s">
                                <h4 style="color:#00225a;margin-bottom:8px">
                                    {{ $cat->getTranslation('name', app()->getLocale()) }}
                                </h4>
                                <span style="font-size:13px;color:#999">
                                    {{ $cat->products()->where('is_active', true)->count() }}
                                    {{ __('messages.products') }}
                                </span>
                                @if($cat->children->count())
                                    <ul style="list-style:none;padding:0;margin:12px 0 0;border-top:1px solid #f0f0f0;padding-top:12px">
                                        @foreach($cat->children->take(4) as $child)
                                            <li style="font-size:13px;padding:3px 0;color:#666">
                                                {{ $child->getTranslation('name', app()->getLocale()) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-10">
                        <p class="text-muted">{{ __('messages.no_products') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection
