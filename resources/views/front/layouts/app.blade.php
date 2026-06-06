<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['fa','ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! JsonLd::generate() !!}

    <title>@yield('title', \App\Models\Setting::get('site_name', config('app.name')))</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/ionicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/font-awesome.min.css') }}" />

    {{-- Plugin CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/jquery-ui.min.css') }}">

    @stack('styles')

    {{-- Style CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- Vazirmatn Font برای فارسی --}}
    @if(in_array(app()->getLocale(), ['fa', 'ar']))
        <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body, * { font-family: 'Vazirmatn', sans-serif !important; }
            .main-header_area, .footer-area, .blog-content, .service-item,
            .project-content, .counter-item, .team-content, .testimonial-content {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endif
</head>

<body>
<div class="main-wrapper">

    {{-- Header --}}
    @include('front.layouts.header')

    {{-- Flash Messages --}}
    @if(session('success') || session('error') || session('info'))
        <div class="container pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif

    {{-- Main Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('front.layouts.footer')

    {{-- Scroll To Top --}}
    <a class="scroll-to-top" href="#">
        <i class="ion-android-arrow-up"></i>
    </a>

</div>

{{-- Vendor JS --}}
<script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery-migrate-3.3.2.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/modernizr-3.11.2.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery.waypoints.js') }}"></script>

{{-- Plugins JS --}}
<script src="{{ asset('assets/js/plugins/wow.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/tippy.min.js') }}"></script>

@stack('scripts')

{{-- Main JS --}}
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
