@extends('front.layouts.app')
@section('title', 'اخبار')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
@endpush

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'آخرین اخبار',
        'title'    => 'اخبار و مقالات',
    ])

    <div class="blog-area py-140">
        <div class="container">
            <div class="row">

                {{-- اخبار --}}
                <div class="col-lg-8 order-lg-1 order-2">
                    <div class="blog-wrap row">
                        @forelse($posts as $post)
                            <div class="col-md-6 {{ !$loop->first ? 'pt-6' : '' }}">
                                <div class="blog-item">
                                    <div class="inner-item">
                                        <a class="blog-img"
                                           href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                                            <img class="img-full" src="{{ $post->cover_url }}"
                                                 alt="{{ $post->getTranslation('title', app()->getLocale()) }}"
                                                 style="height:220px;object-fit:cover">
                                        </a>
                                        <div class="blog-content">
                                        <span class="blog-meta">
                                            {{ $post->author?->name }}
                                            &nbsp;—&nbsp;
                                            {{ $post->published_at?->format('d M Y') }}
                                        </span>
                                            <h3 class="title mb-3">
                                                <a href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                                                    {{ $post->getTranslation('title', app()->getLocale()) }}
                                                </a>
                                            </h3>
                                            <p class="mb-4">
                                                {{ Str::limit($post->getTranslation('excerpt', app()->getLocale()), 100) }}
                                            </p>
                                            <ul class="blog-button-wrap">
                                                <li>
                                                    <a class="btn btn-link p-0"
                                                       href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                                                        ادامه مطلب
                                                    </a>
                                                </li>
                                                @if($post->reading_time)
                                                    <li>
                                                        <span>{{ $post->reading_time }} دقیقه</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-10">
                                <i class="fa fa-newspaper-o fa-3x text-muted mb-4 d-block"></i>
                                <h4 class="text-muted">خبری منتشر نشده است</h4>
                            </div>
                        @endforelse

                        {{-- Pagination --}}
                        @if($posts->hasPages())
                            <div class="col-lg-12 pt-10">
                                <div class="pagination-wrap">
                                    <nav>
                                        <ul class="pagination pagination-custom justify-content-center">
                                            <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ $posts->previousPageUrl() }}">
                                                    <i class="ion-ios-arrow-back"></i>
                                                </a>
                                            </li>
                                            @for($i = 1; $i <= $posts->lastPage(); $i++)
                                                <li class="page-item {{ $posts->currentPage() === $i ? 'active' : '' }}">
                                                    <a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                                                </li>
                                            @endfor
                                            <li class="page-item {{ !$posts->hasMorePages() ? 'disabled' : '' }}">
                                                <a class="page-link" href="{{ $posts->nextPageUrl() }}">
                                                    <i class="ion-ios-arrow-forward"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4 order-lg-2 order-1 pt-10 pt-lg-0">
                    <div class="sidebar-wrap">

                        {{-- جستجو --}}
                        <div class="sidebar-widget sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">جستجو</h3>
                            <form action="{{ route('search') }}" method="GET">
                                <div class="form-field d-flex">
                                    <input class="input-field" type="search" name="q"
                                           value="{{ request('q') }}" placeholder="جستجو...">
                                    <button type="submit"
                                            class="btn btn-secondary btn-primary-hover rounded-0">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- آخرین اخبار --}}
                        <div class="sidebar-widget sidebar-list-wrap sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">آخرین اخبار</h3>
                            @foreach(\App\Models\Post::published()->with('media')->limit(4)->get() as $recentPost)
                                <div class="list-item with-border">
                                    <a href="{{ route('posts.show', $recentPost->getTranslation('slug', app()->getLocale())) }}"
                                       class="list-img">
                                        <img src="{{ $recentPost->thumb_url }}" alt="">
                                    </a>
                                    <div class="list-content">
                                        <h3 class="title mb-1">
                                            <a href="{{ route('posts.show', $recentPost->getTranslation('slug', app()->getLocale())) }}">
                                                {{ Str::limit($recentPost->getTranslation('title', app()->getLocale()), 50) }}
                                            </a>
                                        </h3>
                                        <span class="list-meta">
                                        <i class="ion-md-calendar"></i>
                                        {{ $recentPost->published_at?->format('d M Y') }}
                                    </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- نمایشگاه‌های آینده --}}
                        <div class="sidebar-widget sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">نمایشگاه‌های آینده</h3>
                            @foreach(\App\Models\Event::upcoming()->limit(3)->get() as $event)
                                <div class="list-item with-border">
                                    <div class="list-content">
                                        <h3 class="title mb-1">
                                            <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                                                {{ Str::limit($event->getTranslation('title', app()->getLocale()), 50) }}
                                            </a>
                                        </h3>
                                        <span class="list-meta">
                                        <i class="ion-md-calendar"></i>
                                        {{ $event->starts_at?->format('d M Y') }}
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
