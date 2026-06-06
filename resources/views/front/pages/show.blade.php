@extends('front.layouts.app')
@section('title', $page->getTranslation('title', app()->getLocale()))

@section('content')

    @include('front.components.breadcrumb', [
        'title' => $page->getTranslation('title', app()->getLocale()),
    ])

    <div class="py-140">
        <div class="container">
            @if($page->template === 'full-width')
                <div class="row">
                    <div class="col-12">
                        <div style="line-height:2;font-size:16px">
                            {!! $page->getTranslation('content', app()->getLocale()) !!}
                        </div>
                    </div>
                </div>
            @elseif($page->template === 'sidebar')
                <div class="row">
                    <div class="col-lg-8">
                        <div style="line-height:2;font-size:16px">
                            {!! $page->getTranslation('content', app()->getLocale()) !!}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="sidebar-wrap">
                            <div class="sidebar-widget sidebar-common mb-8" data-bg-color="#f4f8ff">
                                <h3 class="sidebar-title mb-5">تماس با ما</h3>
                                <ul class="widget-list-item">
                                    @if(\App\Models\Setting::get('site_phone'))
                                        <li>
                                            <a href="tel:{{ \App\Models\Setting::get('site_phone') }}">
                                                <i class="fa fa-phone me-2"></i>
                                                {{ \App\Models\Setting::get('site_phone') }}
                                            </a>
                                        </li>
                                    @endif
                                    @if(\App\Models\Setting::get('site_email'))
                                        <li>
                                            <a href="mailto:{{ \App\Models\Setting::get('site_email') }}">
                                                <i class="fa fa-envelope me-2"></i>
                                                {{ \App\Models\Setting::get('site_email') }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- default --}}
                <div class="row">
                    <div class="col-lg-10 mx-auto">
                        @if($page->getFirstMediaUrl('cover'))
                            <img src="{{ $page->getFirstMediaUrl('cover', 'thumb') }}"
                                 class="img-full mb-8"
                                 alt="{{ $page->getTranslation('title', app()->getLocale()) }}"
                                 style="max-height:400px;object-fit:cover">
                        @endif
                        <div style="line-height:2;font-size:16px">
                            {!! $page->getTranslation('content', app()->getLocale()) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
