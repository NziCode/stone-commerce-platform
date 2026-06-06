<div class="col-md-6 col-lg-4 mb-8">
    <div class="blog-item">
        <div class="inner-item">
            <a class="blog-img"
               href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                <img class="img-full" src="{{ $event->cover_url }}"
                     alt="{{ $event->getTranslation('title', app()->getLocale()) }}"
                     style="height:220px;object-fit:cover">
            </a>
            <div class="blog-content">
                <span class="blog-meta d-flex align-items-center gap-3">
                    @if($event->starts_at)
                        <span><i class="fa fa-calendar me-1"></i>{{ $event->starts_at->format('d M Y') }}</span>
                    @endif
                    @if($event->city)
                        <span><i class="fa fa-map-marker me-1"></i>{{ $event->city }}</span>
                    @endif
                </span>
                <h3 class="title mb-2">
                    <a href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                        {{ $event->getTranslation('title', app()->getLocale()) }}
                    </a>
                </h3>
                @if($event->booth_number)
                    <p class="mb-3 text-muted" style="font-size:13px">
                        غرفه: {{ $event->booth_number }}
                        @if($event->hall_number) — سالن: {{ $event->hall_number }} @endif
                    </p>
                @endif
                <ul class="blog-button-wrap">
                    <li>
                        <a class="btn btn-link p-0"
                           href="{{ route('events.show', $event->getTranslation('slug', app()->getLocale())) }}">
                            اطلاعات بیشتر
                        </a>
                    </li>
                    <li>
                        <span class="badge {{ isset($finished) && $finished ? 'bg-secondary' : ($event->isOngoing() ? 'bg-success' : 'bg-primary') }}">
                            {{ $event->status_label }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
