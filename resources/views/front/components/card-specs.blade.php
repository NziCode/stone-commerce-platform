{{-- The specs of a stone on a storefront card: dimensions on one line, the weight, then the other card attributes.
     One place for every card (home, product list, categories, search, wishlist). Needs $product with its attributes loaded. --}}
@php $specRows = \App\Support\StoneCardSpecs::rows($product); @endphp

@if($specRows)
    <ul class="mt-pcard-attrs">
        @foreach($specRows as $row)
            <li>
                <span class="mt-pcard-attr-label">{{ $row['label'] }}:</span>
                <span class="mt-pcard-attr-value"><bdi dir="ltr">{{ $row['value'] }}</bdi></span>
            </li>
        @endforeach
    </ul>
@endif
