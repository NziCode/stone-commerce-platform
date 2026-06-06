@extends('front.layouts.app')
@section('title', $event->getTranslation('title', app()->getLocale()))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'نمایشگاه‌ها',
        'title'    => $event->getTranslation('title', app()->getLocale()),
    ])

    <div class="blog-details-area py-140">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">

                    <img class="img-full mb-6" src="{{ $event->cover_url }}"
                         alt="{{ $event->getTranslation('title', app()->getLocale()) }}"
                         style="max-height:450px;object-fit:cover;width:100%">

                    {{-- اطلاعات --}}
                    <div class="row mb-8">
                        @if($event->starts_at)
                            <div class="col-sm-6 mb-4">
                                <div class="counter-item p-5 border">
                                    <i class="fa fa-calendar fa-2x text-primary mb-2 d-block"></i>
                                    <h5>تاریخ برگزاری</h5>
                                    <p class="mb-0">{{ $event->starts_at->format('d M Y') }}
                                        @if($event->ends_at) — {{ $event->ends_at->format('d M Y') }} @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if($event->getTranslation('location', app()->getLocale()))
                            <div class="col-sm-6 mb-4">
                                <div class="counter-item p-5 border">
                                    <i class="fa fa-map-marker fa-2x text-primary mb-2 d-block"></i>
                                    <h5>مکان</h5>
                                    <p class="mb-0">
                                        {{ $event->getTranslation('location', app()->getLocale()) }}
                                        @if($event->city), {{ $event->city }} @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if($event->booth_number)
                            <div class="col-sm-6 mb-4">
                                <div class="counter-item p-5 border">
                                    <i class="fa fa-building fa-2x text-primary mb-2 d-block"></i>
                                    <h5>غرفه</h5>
                                    <p class="mb-0">
                                        شماره غرفه: {{ $event->booth_number }}
                                        @if($event->hall_number) — سالن {{ $event->hall_number }} @endif
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if($event->website_url)
                            <div class="col-sm-6 mb-4">
                                <div class="counter-item p-5 border">
                                    <i class="fa fa-globe fa-2x text-primary mb-2 d-block"></i>
                                    <h5>وبسایت</h5>
                                    <a href="{{ $event->website_url }}" target="_blank" class="text-primary">
                                        {{ $event->website_url }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- توضیحات --}}
                    <div style="line-height:2;font-size:16px">
                        {!! $event->getTranslation('description', app()->getLocale()) !!}
                    </div>

                    {{-- گالری --}}
                    @if($event->getMedia('gallery')->count())
                        <div class="mt-8">
                            <h4 class="mb-4">گالری تصاویر</h4>
                            <div class="row">
                                @foreach($event->getMedia('gallery') as $img)
                                    <div class="col-md-4 mb-4">
                                        <a href="{{ $img->getUrl() }}" target="_blank">
                                            <img src="{{ $img->getUrl('thumb') }}" class="img-full"
                                                 style="height:180px;object-fit:cover">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4 pt-10 pt-lg-0">
                    <div class="sidebar-wrap">
                        <div class="sidebar-widget sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">نمایشگاه‌های آینده</h3>
                            @foreach(\App\Models\Event::upcoming()->where('id','!=',$event->id)->limit(4)->get() as $upcoming)
                                <div class="list-item with-border">
                                    <div class="list-content">
                                        <h3 class="title mb-1" style="font-size:15px">
                                            <a href="{{ route('events.show', $upcoming->getTranslation('slug', app()->getLocale())) }}">
                                                {{ Str::limit($upcoming->getTranslation('title', app()->getLocale()), 50) }}
                                            </a>
                                        </h3>
                                        <span class="list-meta">
                                        <i class="ion-md-calendar"></i>
                                        {{ $upcoming->starts_at?->format('d M Y') }}
                                    </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
