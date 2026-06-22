@php $locale = app()->getLocale(); $isWished = auth()->check() && auth()->user()->hasWishlisted($product->id); @endphp

<div class="col-lg-4 col-md-6">
    <div class="mt-pcard" style="position:relative">

        {{-- Wishlist button --}}
        @auth
            <form action="{{ route('wishlist.toggle', $product) }}" method="POST"
                  style="position:absolute;top:12px;inset-inline-end:12px;z-index:5">
                @csrf
                <button type="submit"
                        class="mt-pcard-wish {{ $isWished ? 'is-active' : '' }}"
                        title="{{ __('messages.wishlist') }}">
                    <svg viewBox="0 0 24 24"
                         fill="{{ $isWished ? 'currentColor' : 'none' }}"
                         stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
            </form>
        @endauth

        {{-- Image --}}
        <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}"
           class="mt-pcard-img">
            <img src="{{ $product->medium_image_url }}"
                 alt="{{ $product->getTranslation('name', $locale) }}" loading="lazy">
            <span class="mt-pcard-status"
                  style="color:{{ $product->status === 'available' ? '#1f9d55' : ($product->status === 'sold' ? '#e0473a' : '#e0a400') }}">
                {{ $product->status_label }}
            </span>
        </a>

        {{-- Body --}}
        <div class="mt-pcard-body">
            <span class="mt-pcard-cat">
                {{ $product->primaryCategory()?->getTranslation('name', $locale) ?? '' }}
            </span>
            <h3 class="mt-pcard-title">
                <a href="{{ route('products.show', $product->getTranslation('slug', $locale)) }}">
                    {{ $product->getTranslation('name', $locale) }}
                </a>
            </h3>
            @if($product->sku)
                <span class="mt-pcard-sku">{{ $product->sku }}</span>
            @endif
            <div class="mt-pcard-foot">
                <span class="mt-price">
                    @if($product->price_on_request)
                        <small>{{ __('messages.price') }}</small>{{ __('messages.price_on_request') }}
                    @elseif($product->price)
                        {{ number_format($product->price) }}
                        @if($product->price_usd)
                            <small>${{ number_format($product->price_usd, 0) }}</small>
                        @endif
                    @endif
                </span>
                @if($product->isAvailable())
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="mt-pcard-add"
                                title="{{ __('messages.add_to_cart') ?? 'Add to cart' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
