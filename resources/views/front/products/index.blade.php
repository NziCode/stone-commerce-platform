@extends('front.layouts.app')

@section('title', 'محصولات')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'سنگ‌های طبیعی',
        'title'    => 'محصولات',
        'desc'     => 'بهترین سنگ‌های طبیعی از معادن معتبر ایران و جهان',
    ])

    <div class="product-area py-140">
        <div class="container">
            <div class="row">

                {{-- Sidebar --}}
                <div class="col-lg-4 order-lg-1 order-2 pt-10 pt-lg-0">
                    <div class="sidebar-wrap">

                        {{-- جستجو --}}
                        <div class="sidebar-single-item mb-8">
                            <h3 class="sidebar-title">جستجو</h3>
                            <div class="sidebar-body">
                                <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                                    <div class="form-field d-flex">
                                        <input class="input-field" type="search" name="q"
                                               value="{{ request('q') }}"
                                               placeholder="نام محصول، کد...">
                                        <button type="submit"
                                                class="btn btn-secondary btn-primary-hover rounded-0">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- دسته‌بندی‌ها --}}
                        <div class="sidebar-single-item mb-8">
                            <h3 class="sidebar-title">دسته‌بندی‌ها</h3>
                            <div class="sidebar-body">
                                <ul>
                                    <li>
                                        <a href="{{ route('products.index', request()->except('category')) }}"
                                           class="{{ !request('category') ? 'text-primary fw-bold' : '' }}">
                                            همه دسته‌ها
                                        </a>
                                    </li>
                                    @foreach($categories as $cat)
                                        <li>
                                            <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $cat->id])) }}"
                                               class="{{ request('category') == $cat->id ? 'text-primary fw-bold' : '' }}">
                                                {{ $cat->getTranslation('name', app()->getLocale()) }}
                                                <span class="text-muted">({{ $cat->products_count ?? 0 }})</span>
                                            </a>
                                        </li>
                                        @foreach($cat->children as $child)
                                            <li style="padding-right: 15px;">
                                                <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $child->id])) }}"
                                                   class="{{ request('category') == $child->id ? 'text-primary fw-bold' : '' }}">
                                                    — {{ $child->getTranslation('name', app()->getLocale()) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- وضعیت --}}
                        <div class="sidebar-single-item mb-8">
                            <h3 class="sidebar-title">وضعیت</h3>
                            <div class="sidebar-body">
                                <form action="{{ route('products.index') }}" method="GET">
                                    @foreach(request()->except('status') as $k => $v)
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endforeach
                                    <ul>
                                        @foreach(['' => 'همه', 'available' => 'موجود', 'reserved' => 'رزرو شده', 'sold' => 'فروخته شده'] as $val => $label)
                                            <li>
                                                <label class="d-flex align-items-center gap-2" style="cursor:pointer">
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

                        {{-- محدوده قیمت --}}
                        <div class="sidebar-single-item mb-8">
                            <h3 class="sidebar-title">قیمت</h3>
                            <div class="sidebar-body">
                                <form action="{{ route('products.index') }}" method="GET">
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

                        {{-- حذف فیلترها --}}
                        @if(request()->hasAny(['q','status','category','min_price','max_price']))
                            <a href="{{ route('products.index') }}"
                               class="btn btn-outline-secondary btn-sm w-100 rounded-0">
                                <i class="fa fa-times me-1"></i> حذف فیلترها
                            </a>
                        @endif

                    </div>
                </div>

                {{-- محصولات --}}
                <div class="col-lg-8 order-lg-2 order-1">

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between mb-6">
                        <p class="mb-0 text-muted">
                            {{ $products->total() }} محصول یافت شد
                        </p>
                        <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center gap-2">
                            @foreach(request()->except('sort') as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <select name="sort" class="form-select form-select-sm rounded-0"
                                    onchange="this.form.submit()" style="width:auto">
                                <option value="">پیش‌فرض</option>
                                <option value="newest"     {{ request('sort') === 'newest'     ? 'selected' : '' }}>جدیدترین</option>
                                <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>ارزان‌ترین</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>گران‌ترین</option>
                                <option value="views"      {{ request('sort') === 'views'      ? 'selected' : '' }}>پربازدیدترین</option>
                            </select>
                        </form>
                    </div>

                    @if($products->count())
                        <div class="product-wrap row">
                            @foreach($products as $product)
                                @include('front.components.product-card', ['product' => $product, 'loop' => $loop])
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="col-lg-12 pt-10">
                            <div class="pagination-wrap">
                                <nav>
                                    <ul class="pagination pagination-custom justify-content-center">
                                        {{-- Previous --}}
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

                                        {{-- Next --}}
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
                            <p>فیلترها را تغییر دهید یا
                                <a href="{{ route('products.index') }}">همه محصولات</a>
                                را مشاهده کنید.
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection
