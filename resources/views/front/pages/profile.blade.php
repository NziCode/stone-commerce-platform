@extends('front.layouts.app')

@section('title', $page->getTranslation('title', $locale) . ' — ' . \App\Models\Setting::get('site_name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}?v={{ @filemtime(public_path('assets/css/about.css')) ?: 1 }}">
@endpush

@section('content')
    @include('front.components.breadcrumb', [
        'subtitle' => \App\Models\Setting::get('site_name'),
        'title'    => $page->getTranslation('title', $locale),
        'desc'     => $page->getTranslation('excerpt', $locale),
    ])

    <div class="mt-section ab-founder">
        <div class="mt-container">
            @include('front.pages.partials.founder-card', ['founder' => $page, 'locale' => $locale])
        </div>
    </div>
@endsection
