{{-- Small slider inside the home hero, where the banner slider used to be: the main categories — export / saw-cut / top-cut —
     each with a picture, leading to the product list filtered by that group. Stone types stay in the categories grid below.
     Native scroll-snap: swipe on phones, arrows on wide screens. --}}
@php $locale = app()->getLocale(); @endphp

@if($mainCategories->count())
    <div class="mt-container mt-quick">
        <div class="mt-quick-block" data-mt-slider>
            <div class="mt-quick-head">
                <h2 class="mt-quick-title">{{ __('messages.shop_by_main_category') }}</h2>
                <div class="mt-quick-nav">
                    <button type="button" data-dir="-1" aria-label="‹" hidden><span>‹</span></button>
                    <button type="button" data-dir="1" aria-label="›" hidden><span>›</span></button>
                </div>
            </div>
            <div class="mt-slider-track mt-slider-track--groups">
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
        </div>
    </div>

    @once
        @push('scripts')
            <script>
                // arrows for the slider: shown only where the track really overflows
                document.querySelectorAll('[data-mt-slider]').forEach(function (block) {
                    var track = block.querySelector('.mt-slider-track');
                    var buttons = block.querySelectorAll('.mt-quick-nav button');
                    if (!track || !buttons.length) return;

                    var rtl = getComputedStyle(track).direction === 'rtl';
                    var refresh = function () {
                        var overflow = track.scrollWidth > track.clientWidth + 4;
                        buttons.forEach(function (b) { b.hidden = !overflow; });
                    };

                    buttons.forEach(function (b) {
                        b.addEventListener('click', function () {
                            var step = Math.max(track.clientWidth * 0.8, 200) * Number(b.dataset.dir) * (rtl ? -1 : 1);
                            track.scrollBy({ left: step, behavior: 'smooth' });
                        });
                    });

                    window.addEventListener('resize', refresh);
                    refresh();
                });
            </script>
        @endpush
    @endonce
@endif
