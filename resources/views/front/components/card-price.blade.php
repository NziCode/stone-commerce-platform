{{-- The price line of a storefront card: the amount (with the dollar price under it), or - when the price is on request -
     a "Request a quote" button that opens the inquiry pop-up. No "price on request" text and no separate "Price" label. --}}
@if($product->price_on_request)
    @include('front.components.inquiry-button', ['product' => $product, 'class' => 'mt-pcard-inquiry'])
@else
    <span class="mt-price">
        @if($product->price)
            {{ number_format($product->price) }} {{ __('messages.currency_rial') }}
            @if($product->price_usd)
                <small>${{ number_format($product->price_usd, 0) }}</small>
            @endif
        @endif
    </span>
@endif
