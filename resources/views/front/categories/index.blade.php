@extends('front.layouts.app')
@section('title', 'دسته‌بندی‌ها')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'سنگ‌های طبیعی',
        'title'    => 'دسته‌بندی‌ها',
    ])

    <div class="product-area py-140">
        <div class="container">
            <div class="row">
                @foreach($categories as $category)
                    <div class="col-lg-4 col-sm-6 pt-8 pt-lg-0 {{ $loop->index >= 3 ? 'pt-8' : '' }}">
                        <div class="project-item">
                            <a class="project-img d-block"
                               href="{{ route('categories.show', $category->getTranslation('slug', app()->getLocale())) }}">
                                <img class="img-full" src="{{ $category->image_url }}"
                                     alt="{{ $category->getTranslation('name', app()->getLocale()) }}"
                                     style="height:280px;object-fit:cover">
                            </a>
                            <div class="project-content">
                            <span class="sub-title">
                                {{ $category->products()->available()->count() }} محصول موجود
                            </span>
                                <h3 class="title mb-0">
                                    <a href="{{ route('categories.show', $category->getTranslation('slug', app()->getLocale())) }}">
                                        {{ $category->getTranslation('name', app()->getLocale()) }}
                                    </a>
                                </h3>
                                @if($category->children->count())
                                    <span class="text-muted" style="font-size:13px">
                                    {{ $category->children->count() }} زیردسته
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
