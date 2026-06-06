@extends('front.layouts.app')
@section('title', 'جستجو: ' . $query)

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'نتایج',
        'title'    => 'جستجو برای: ' . $query,
    ])

    <div class="py-140">
        <div class="container">

            {{-- فرم جستجو --}}
            <div class="row mb-10">
                <div class="col-lg-6 mx-auto">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="form-field d-flex">
                            <input class="input-field" type="search" name="q"
                                   value="{{ $query }}" placeholder="جستجو...">
                            <button type="submit"
                                    class="btn btn-secondary btn-primary-hover rounded-0">
                                <i class="fa fa-search me-1"></i> جستجو
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(strlen($query) < 2)
                <div class="text-center py-10">
                    <p class="text-muted">حداقل ۲ کاراکتر وارد کنید.</p>
                </div>
            @elseif($products->isEmpty() && $posts->isEmpty())
                <div class="text-center py-10">
                    <i class="fa fa-search fa-3x text-muted mb-4 d-block"></i>
                    <h4 class="text-muted">نتیجه‌ای یافت نشد</h4>
                    <p>عبارت دیگری جستجو کنید یا
                        <a href="{{ route('products.index') }}">همه محصولات</a>
                        را ببینید.
                    </p>
                </div>
            @else
                {{-- محصولات --}}
                @if($products->count())
                    <div class="mb-12">
                        <h3 class="mb-6">محصولات ({{ $products->count() }})</h3>
                        <div class="row">
                            @foreach($products as $product)
                                @include('front.components.product-card', ['product' => $product])
                            @endforeach
                        </div>
                        @if($products->count() >= 12)
                            <div class="text-center mt-6">
                                <a href="{{ route('products.index', ['q' => $query]) }}"
                                   class="btn btn-secondary btn-primary-hover">
                                    مشاهده همه نتایج محصولات
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- اخبار --}}
                @if($posts->count())
                    <div>
                        <h3 class="mb-6">اخبار ({{ $posts->count() }})</h3>
                        <div class="row">
                            @foreach($posts as $post)
                                <div class="col-md-6 col-lg-4 mb-6">
                                    <div class="blog-item">
                                        <div class="inner-item">
                                            <a class="blog-img"
                                               href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                                                <img class="img-full" src="{{ $post->thumb_url }}"
                                                     style="height:200px;object-fit:cover"
                                                     alt="{{ $post->getTranslation('title', app()->getLocale()) }}">
                                            </a>
                                            <div class="blog-content">
                                                <h3 class="title mb-2">
                                                    <a href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                                                        {{ Str::limit($post->getTranslation('title', app()->getLocale()), 60) }}
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
            @endif
        </div>
    </div>

@endsection
