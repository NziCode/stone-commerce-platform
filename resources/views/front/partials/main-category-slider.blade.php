{{-- The main categories — export / saw-cut / top-cut — inside the home hero, where the banner slider used to be: each card
     has a picture and leads to the product list filtered by that group. Centred on wide screens, swipeable on phones.
     Stone types stay in the categories grid below. --}}
@php $locale = app()->getLocale(); @endphp

@if($mainCategories->count())
    <nav class="mt-container mt-quick" aria-label="{{ __('messages.shop_by_main_category') }}">
        <div class="mt-slider-track">
            @foreach($mainCategories as $group)
                <a class="mt-gcard" href="{{ $group->url() }}">
                    <span class="mt-gcard-img">
                        @if($group->imageUrl())
                            <img src="{{ $group->imageUrl() }}" alt="" loading="lazy" width="700" height="460">
                        @endif
                    </span>
                    <span class="mt-gcard-body">
                        <strong>{{ $group->getTranslation('name', $locale) }}</strong>
                        <small>{{ __('messages.stones_count', ['count' => $group->active_products_count]) }}</small>
                        <em>{{ __('messages.view_stones') }}
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </em>
                    </span>
                </a>
            @endforeach
        </div>
    </nav>
@endif
