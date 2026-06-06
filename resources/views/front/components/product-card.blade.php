<div class="col-lg-4 col-sm-6 {{ isset($loop) && !$loop->first ? 'pt-8 pt-lg-0' : '' }}">
    <div class="product-item text-center">
        <div class="product-img">
            <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                <img src="{{ $product->medium_image_url }}"
                     alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
            </a>
            <div class="add-action">
                <ul>
                    @if($product->isAvailable())
                        <li>
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="btn btn-custom md-size btn-primary btn-secondary-hover">
                                    افزودن به سبد
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <span class="btn btn-custom md-size btn-secondary" style="cursor:default">
                                {{ $product->status_label }}
                            </span>
                        </li>
                    @endif
                    @auth
                        <li>
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="btn btn-custom md-size btn-outline-secondary"
                                        title="علاقه‌مندی">
                                    <i class="fa fa-heart{{ auth()->user()->hasWishlisted($product->id) ? '' : '-o' }}"></i>
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
        <div class="product-content py-4">
            {{-- وضعیت --}}
            <span class="d-block mb-1" style="font-size:12px;
                color:{{ $product->status === 'available' ? '#28a745' : ($product->status === 'sold' ? '#dc3545' : '#ffc107') }}">
                {{ $product->status_label }}
            </span>
            <h2 class="title mb-0">
                <a href="{{ route('products.show', $product->getTranslation('slug', app()->getLocale())) }}">
                    {{ $product->getTranslation('name', app()->getLocale()) }}
                </a>
            </h2>
            @if($product->sku)
                <small class="text-muted d-block">{{ $product->sku }}</small>
            @endif
            <div class="price-box mt-2">
                @if($product->price_on_request)
                    <span class="new-price" style="font-size:14px">قیمت با تماس</span>
                @elseif($product->price)
                    <span class="new-price">{{ number_format($product->price) }}</span>
                    @if($product->price_usd)
                        <span class="old-price" style="font-size:13px">
                            ${{ number_format($product->price_usd, 0) }}
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
