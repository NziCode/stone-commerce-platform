@if(($product->status ?? null) === 'sold')
    <div class="mt-sold-stamp{{ ($size ?? null) === 'lg' ? ' mt-sold-stamp--lg' : '' }}">{{ $product->status_label }}</div>
@endif
