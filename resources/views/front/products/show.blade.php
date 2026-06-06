@extends('front.layouts.app')

@section('title', $product->getTranslation('name', app()->getLocale()))

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper-bundle.min.css') }}">
@endpush

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => $product->primaryCategory()?->getTranslation('name', app()->getLocale()) ?? 'محصولات',
        'title'    => $product->getTranslation('name', app()->getLocale()),
    ])

    <div class="product-details-area py-140">
        <div class="container">
            <div class="row">

                {{-- گالری تصاویر --}}
                <div class="col-lg-6 mb-8 mb-lg-0">

                    {{-- تصویر اصلی --}}
                    <div class="product-details-img mb-4">
                        <div class="swiper-container product-gallery-main">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img class="img-full" src="{{ $product->main_image_url }}"
                                         alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                </div>
                                @foreach($product->gallery_urls as $img)
                                    <div class="swiper-slide">
                                        <img class="img-full" src="{{ $img['large'] ?? $img['medium'] }}"
                                             alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>

                    {{-- Thumbnails --}}
                    @if(count($product->gallery_urls) > 0)
                        <div class="swiper-container product-gallery-thumb">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="{{ $product->thumb_url }}" class="img-full"
                                         alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                </div>
                                @foreach($product->gallery_urls as $img)
                                    <div class="swiper-slide">
                                        <img src="{{ $img['thumb'] }}" class="img-full"
                                             alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ویدیوها --}}
                    @if($product->getMedia('videos')->count())
                        <div class="mt-4">
                            @foreach($product->getMedia('videos') as $video)
                                <video controls class="w-100 mb-3" style="max-height:300px">
                                    <source src="{{ $video->getUrl() }}" type="video/mp4">
                                </video>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- اطلاعات محصول --}}
                <div class="col-lg-6">
                    <div class="product-details-content">

                        {{-- وضعیت --}}
                        <span class="badge mb-3 px-3 py-2"
                              style="background:{{ $product->status === 'available' ? '#28a745' : ($product->status === 'sold' ? '#dc3545' : '#ffc107') }};
                                 color:white; font-size:14px">
                        {{ $product->status_label }}
                    </span>

                        <h1 class="title mb-3" style="font-size:28px">
                            {{ $product->getTranslation('name', app()->getLocale()) }}
                        </h1>

                        @if($product->sku || $product->mine_code)
                            <div class="d-flex gap-4 mb-4 text-muted" style="font-size:14px">
                                @if($product->sku)
                                    <span><i class="fa fa-barcode me-1"></i> کد: {{ $product->sku }}</span>
                                @endif
                                @if($product->mine_code)
                                    <span><i class="fa fa-industry me-1"></i> کد معدن: {{ $product->mine_code }}</span>
                                @endif
                            </div>
                        @endif

                        {{-- قیمت --}}
                        <div class="price-box mb-5" style="font-size:22px">
                            @if($product->price_on_request)
                                <span class="new-price" style="font-size:20px">قیمت با تماس</span>
                            @else
                                @if($product->price)
                                    <span class="new-price">{{ number_format($product->price) }} تومان</span>
                                @endif
                                @if($product->price_usd)
                                    <span class="old-price ms-2">${{ number_format($product->price_usd, 0) }}</span>
                                @endif
                            @endif
                        </div>

                        {{-- توضیح کوتاه --}}
                        @if($product->getTranslation('short_description', app()->getLocale()))
                            <p class="mb-5" style="font-size:16px;line-height:1.8">
                                {{ $product->getTranslation('short_description', app()->getLocale()) }}
                            </p>
                        @endif

                        {{-- ابعاد --}}
                        @if($product->dimensions)
                            <div class="mb-4">
                                <strong>ابعاد:</strong> {{ $product->dimensions }}
                            </div>
                        @endif
                        @if($product->weight_kg)
                            <div class="mb-4">
                                <strong>وزن:</strong> {{ $product->weight_kg }} کیلوگرم
                            </div>
                        @endif
                        @if($product->area_m2)
                            <div class="mb-4">
                                <strong>متراژ:</strong> {{ $product->area_m2 }} متر مربع
                            </div>
                        @endif

                        {{-- دسته‌بندی‌ها --}}
                        @if($product->categories->count())
                            <div class="mb-5">
                                <strong>دسته‌بندی:</strong>
                                @foreach($product->categories as $cat)
                                    <a href="{{ route('categories.show', $cat->getTranslation('slug', app()->getLocale())) }}"
                                       class="badge bg-secondary ms-1 text-decoration-none">
                                        {{ $cat->getTranslation('name', app()->getLocale()) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- دکمه‌ها --}}
                        <div class="button-wrap d-flex gap-3 mb-6">
                            @if($product->isAvailable())
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-custom btn-primary btn-secondary-hover">
                                        <i class="fa fa-shopping-cart me-2"></i>
                                        افزودن به سبد خرید
                                    </button>
                                </form>
                            @elseif($product->isSold())
                                <span class="btn btn-secondary" style="cursor:default">
                                فروخته شده
                            </span>
                            @elseif($product->isReserved())
                                <span class="btn btn-warning" style="cursor:default">
                                رزرو شده
                            </span>
                            @endif

                            @auth
                                <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-custom btn-secondary btn-primary-hover"
                                            title="{{ auth()->user()->hasWishlisted($product->id) ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها' }}">
                                        <i class="fa fa-heart{{ auth()->user()->hasWishlisted($product->id) ? '' : '-o' }}"></i>
                                    </button>
                                </form>
                            @endauth
                        </div>

                        {{-- اشتراک‌گذاری --}}
                        <div class="product-share">
                            <span class="me-2">اشتراک‌گذاری:</span>
                            <ul class="social-link d-inline-flex gap-2">
                                <li>
                                    <a href="https://wa.me/?text={{ urlencode($product->getTranslation('name', app()->getLocale()) . ' ' . url()->current()) }}"
                                       target="_blank">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}"
                                       target="_blank">
                                        <i class="fa fa-telegram"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/shareArticle?url={{ urlencode(url()->current()) }}"
                                       target="_blank">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Tabs: توضیحات / مشخصات / نظرات --}}
            <div class="row mt-10">
                <div class="col-12">
                    <ul class="nav nav-tabs" id="productTabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc">
                                توضیحات
                            </button>
                        </li>
                        @if($product->attributes->count())
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#specs">
                                    مشخصات فنی
                                </button>
                            </li>
                        @endif
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">
                                نظرات ({{ $product->approvedReviews->count() }})
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content border border-top-0 p-6">

                        {{-- توضیحات --}}
                        <div class="tab-pane fade show active" id="desc">
                            <div class="product-desc" style="line-height:2">
                                {!! $product->getTranslation('description', app()->getLocale()) !!}
                            </div>
                        </div>

                        {{-- مشخصات فنی --}}
                        @if($product->attributes->count())
                            <div class="tab-pane fade" id="specs">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                    @foreach($product->attributes as $attr)
                                        <tr>
                                            <th width="30%" style="background:#f8f9fa">
                                                {{ $attr->getTranslation('key', app()->getLocale()) }}
                                            </th>
                                            <td>{{ $attr->display_value }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        {{-- نظرات --}}
                        <div class="tab-pane fade" id="reviews">
                            @if($product->approvedReviews->count())
                                <div class="reviews-list mb-8">
                                    @foreach($product->approvedReviews as $review)
                                        <div class="review-item border-bottom pb-4 mb-4">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div>
                                                    <strong>{{ $review->reviewer_name }}</strong>
                                                    @if($review->reviewer_company)
                                                        <small class="text-muted ms-2">— {{ $review->reviewer_company }}</small>
                                                    @endif
                                                    @if($review->reviewer_country)
                                                        <small class="text-muted ms-2">{{ $review->reviewer_country }}</small>
                                                    @endif
                                                </div>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($review->comment)
                                                <p class="mb-0">{{ $review->comment }}</p>
                                            @endif
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- فرم ثبت نظر --}}
                            @auth
                                <h4 class="mb-4">ثبت نظر</h4>
                                <form action="{{ route('reviews.store', $product) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label">امتیاز شما</label>
                                        <div class="star-rating d-flex gap-2" style="font-size:24px">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label style="cursor:pointer;color:#ffc107">
                                                    <input type="radio" name="rating" value="{{ $i }}"
                                                           style="display:none"
                                                        {{ old('rating') == $i ? 'checked' : '' }}>
                                                    <i class="fa fa-star-o"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">نظر شما</label>
                                        <textarea name="comment" class="form-control rounded-0" rows="4"
                                                  placeholder="تجربه خود از این محصول را بنویسید...">{{ old('comment') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-custom btn-primary btn-secondary-hover">
                                        ثبت نظر
                                    </button>
                                </form>
                            @else
                                <p>
                                    برای ثبت نظر
                                    <a href="{{ route('login') }}" class="text-primary">وارد شوید</a>.
                                </p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            {{-- محصولات مشابه --}}
            @if($relatedProducts->count())
                <div class="related-products mt-14">
                    <div class="section-title mb-8">
                        <h3>محصولات مشابه</h3>
                    </div>
                    <div class="row">
                        @foreach($relatedProducts as $product)
                            @include('front.components.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/swiper-bundle.min.js') }}"></script>
    <script>
        // Gallery Swiper
        var galleryThumb = new Swiper('.product-gallery-thumb', {
            spaceBetween: 10,
            slidesPerView: 4,
            watchSlidesProgress: true,
        });
        var galleryMain = new Swiper('.product-gallery-main', {
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            thumbs: { swiper: galleryThumb },
        });

        // Star Rating
        document.querySelectorAll('.star-rating label').forEach((label, index, labels) => {
            label.addEventListener('click', function() {
                this.querySelector('input').checked = true;
                labels.forEach((l, i) => {
                    l.querySelector('i').className = i <= index ? 'fa fa-star' : 'fa fa-star-o';
                });
            });
            label.addEventListener('mouseover', function() {
                labels.forEach((l, i) => {
                    l.querySelector('i').className = i <= index ? 'fa fa-star' : 'fa fa-star-o';
                });
            });
        });
    </script>
@endpush
