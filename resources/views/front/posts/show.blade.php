@extends('front.layouts.app')
@section('title', $post->getTranslation('title', app()->getLocale()))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'اخبار',
        'title'    => $post->getTranslation('title', app()->getLocale()),
    ])

    <div class="blog-details-area py-140">
        <div class="container">
            <div class="row">

                {{-- محتوا --}}
                <div class="col-lg-8 order-lg-1 order-2">
                    <div class="blog-details-wrap">

                        {{-- تصویر کاور --}}
                        <div class="blog-img mb-6">
                            <img class="img-full" src="{{ $post->cover_url }}"
                                 alt="{{ $post->getTranslation('title', app()->getLocale()) }}"
                                 style="max-height:450px;object-fit:cover;width:100%">
                        </div>

                        {{-- متا --}}
                        <div class="blog-meta-wrap d-flex gap-4 mb-5 text-muted" style="font-size:14px">
                            @if($post->author)
                                <span><i class="fa fa-user me-1"></i> {{ $post->author->name }}</span>
                            @endif
                            <span><i class="fa fa-calendar me-1"></i> {{ $post->published_at?->format('d M Y') }}</span>
                            <span><i class="fa fa-eye me-1"></i> {{ $post->views_count }} بازدید</span>
                            @if($post->reading_time)
                                <span><i class="fa fa-clock-o me-1"></i> {{ $post->reading_time }} دقیقه</span>
                            @endif
                        </div>

                        {{-- محتوا --}}
                        <div class="blog-content-wrap" style="line-height:2;font-size:16px">
                            {!! $post->getTranslation('content', app()->getLocale()) !!}
                        </div>

                        {{-- گالری --}}
                        @if($post->getMedia('gallery')->count())
                            <div class="blog-gallery mt-8">
                                <h4 class="mb-4">گالری تصاویر</h4>
                                <div class="row">
                                    @foreach($post->getMedia('gallery') as $img)
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

                        {{-- اشتراک‌گذاری --}}
                        <div class="blog-share mt-8 pt-6 border-top">
                            <span class="me-3">اشتراک‌گذاری:</span>
                            <ul class="social-link d-inline-flex gap-2">
                                <li>
                                    <a href="https://wa.me/?text={{ urlencode($post->getTranslation('title', app()->getLocale()) . ' ' . url()->current()) }}"
                                       target="_blank"><i class="fa fa-whatsapp"></i></a>
                                </li>
                                <li>
                                    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}"
                                       target="_blank"><i class="fa fa-telegram"></i></a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/shareArticle?url={{ urlencode(url()->current()) }}"
                                       target="_blank"><i class="fa fa-linkedin"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- اخبار مرتبط --}}
                    @if($relatedPosts->count())
                        <div class="related-posts mt-10">
                            <h4 class="mb-6">اخبار مرتبط</h4>
                            <div class="row">
                                @foreach($relatedPosts as $related)
                                    <div class="col-md-4">
                                        <div class="blog-item">
                                            <div class="inner-item">
                                                <a class="blog-img"
                                                   href="{{ route('posts.show', $related->getTranslation('slug', app()->getLocale())) }}">
                                                    <img class="img-full" src="{{ $related->thumb_url }}"
                                                         style="height:160px;object-fit:cover"
                                                         alt="{{ $related->getTranslation('title', app()->getLocale()) }}">
                                                </a>
                                                <div class="blog-content">
                                                    <h3 class="title mb-2" style="font-size:15px">
                                                        <a href="{{ route('posts.show', $related->getTranslation('slug', app()->getLocale())) }}">
                                                            {{ Str::limit($related->getTranslation('title', app()->getLocale()), 60) }}
                                                        </a>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4 order-lg-2 order-1 pt-10 pt-lg-0">
                    <div class="sidebar-wrap">
                        <div class="sidebar-widget sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">جستجو</h3>
                            <form action="{{ route('search') }}" method="GET">
                                <div class="form-field d-flex">
                                    <input class="input-field" type="search" name="q" placeholder="جستجو...">
                                    <button type="submit" class="btn btn-secondary btn-primary-hover rounded-0">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="sidebar-widget sidebar-list-wrap sidebar-common mb-8" data-bg-color="#f4f8ff">
                            <h3 class="sidebar-title mb-5">آخرین اخبار</h3>
                            @foreach(\App\Models\Post::published()->with('media')->where('id','!=',$post->id)->limit(4)->get() as $recentPost)
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
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
