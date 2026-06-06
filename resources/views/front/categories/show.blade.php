@extends('front.layouts.app')
@section('title', $category->getTranslation('name', app()->getLocale()))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'دسته‌بندی',
        'title'    => $category->getTranslation('name', app()->getLocale()),
        'desc'     => $category->getTranslation('excerpt', app()->getLocale()),
    ])

    <div class="product-area py-140">
        <div class="container">

            {{-- زیردسته‌ها --}}
            @if($category->children->count())
                <div class="row mb-10">
                    @foreach($category->children as $child)
                        <div class="col-lg-3 col-sm-6 mb-4">
                            <a href="{{ route('categories.show', $child->getTranslation('slug', app()->getLocale())) }}"
                               class="banner-item text-white d-block"
                               data-bg-image="{{ $child->getFirstMediaUrl('image') ?: asset('assets/images/banner/inner-bg/1-1.png') }}"
                               style="min-height:120px">
                                <div class="banner-content">
                                    <h5 class="title mb-1">
                                        {{ $child->getTranslation('name', app()->getLocale()) }}
                                    </h5>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="row">

                {{-- Sidebar --}}
                <div class="col-lg-4 order-lg-1 order-2 pt-10 pt-lg-0">
                    <div class="sidebar-wrap">

                        {{-- مرتب‌سازی --}}
                        <div class="sidebar-single-item mb-8">
                            <h3 class="sidebar-title">مرتب‌سازی</h3>
                            <div class="sidebar-body">
                                <form action="{{ url()->current() }}" method="GET">
                                    @foreach(request()->except('sort') as $k => $v)
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endforeach
                                    <ul>
                                        @foreach([''=>'پیش‌فرض','newest'=>'جدیدترین','price_asc'=>'ارزان‌ترین','price_desc'=>'گران‌ترین'] as $val => $label)
                                            <li>
                                                <label style="cursor:pointer;display:flex;align-items:center;gap:8px">
                                                    <input type="radio" name="sort" value="{{ $val }}"
                                                           {{ request('sort') === $val || (!request('sort') && $val === '') ? 'checked' : '' }}
                                                           onchange="this.form.submit()">
                                                    {{ $label }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </form>
                            </div>
                        </div>

                        {{-- وضعیت --}}
                        <div class="sidebar-single-item mb-8">
                            <h3 class="sidebar-title">وضعیت</h3>
                            <div class="sidebar-body">
                                <form action="{{ url()->current() }}" method="GET">
                                    @foreach(request()->except('status') as $k => $v)
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endforeach
                                    <ul>
                                        @foreach(['' => 'همه', 'available' => 'موجود', 'reserved' => 'رزرو', 'sold' => 'فروخته شده'] as $val => $label)
                                            <li>
                                                <label style="cursor:pointer;display:flex;align-items:center;gap:8px">
                                                    <input type="radio" name="status" value="{{ $val }}"
                                                           {{ request('status') === $val || (!request('status') && $val === '') ? 'checked' : '' }}
                                                           onchange="this.form.submit()">
                                                    {{ $label }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </form>
                            </div>
                        </div>

                        {{-- قیمت --}}
                        <div class="sidebar-single-item mb-8">
                            <h3 class="sidebar-title">محدوده قیمت</h3>
                            <div class="sidebar-body">
                                <form action="{{ url()->current() }}" method="GET">
                                    @foreach(request()->except(['min_price','max_price']) as $k => $v)
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endforeach
                                    <div class="d-flex gap-2 mb-3">
                                        <input class="input-field" type="number" name="min_price"
                                               value="{{ request('min_price') }}" placeholder="از">
                                        <input class="input-field" type="number" name="max_price"
                                               value="{{ request('max_price') }}" placeholder="تا">
                                    </div>
                                    <button type="submit"
                                            class="btn btn-secondary btn-primary-hover btn-sm rounded-0 w-100">
                                        اعمال
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- لینک به همه محصولات --}}
                        <a href="{{ route('products.index') }}"
                           class="btn btn-outline-secondary btn-sm w-100 rounded-0">
                            مشاهده همه محصولات
                        </a>
                    </div>
                </div>

                {{-- محصولات --}}
                <div class="col-lg-8 order-lg-2 order-1">
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <p class="mb-0 text-muted">{{ $products->total() }} محصول</p>
                    </div>

                    @if($products->count())
                        <div class="product-wrap row">
                            @foreach($products as $product)
                                @include('front.components.product-card', ['product' => $product])
                            @endforeach
                        </div>

                        <div class="col-lg-12 pt-10">
                            <div class="pagination-wrap">
                                <nav>
                                    <ul class="pagination pagination-custom justify-content-center">
                                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $products->previousPageUrl() }}">
                                                <i class="ion-ios-arrow-back"></i>
                                            </a>
                                        </li>
                                        @for($i = 1; $i <= $products->lastPage(); $i++)
                                            <li class="page-item {{ $products->currentPage() === $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $products->nextPageUrl() }}">
                                                <i class="ion-ios-arrow-forward"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <i class="fa fa-search fa-3x text-muted mb-4 d-block"></i>
                            <h4 class="text-muted">محصولی یافت نشد</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
