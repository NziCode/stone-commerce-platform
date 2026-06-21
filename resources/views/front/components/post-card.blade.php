<article class="mt-post h-100">
    <a href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}" class="mt-post-img">
        @if($post->hasMedia('cover'))
            <img src="{{ $post->cover_url }}" alt="{{ $post->getTranslation('title', app()->getLocale()) }}" loading="lazy">
        @else
            <span class="mt-post-img-fallback">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
            </span>
        @endif
    </a>
    <div class="mt-post-body">
        <div class="mt-post-meta">
            <span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                {{ $post->published_at?->diffForHumans() }}
            </span>
            @if($post->reading_time)
                <span>{{ $post->reading_time }} {{ __('messages.min_read') ?? 'min read' }}</span>
            @endif
        </div>
        <h3 class="mt-post-title">
            <a href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
                {{ $post->getTranslation('title', app()->getLocale()) }}
            </a>
        </h3>
        @if($post->getTranslation('excerpt', app()->getLocale()))
            <p class="mt-post-excerpt">{{ Str::limit($post->getTranslation('excerpt', app()->getLocale()), 110) }}</p>
        @endif
        <a class="mt-post-more" href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}">
            {{ __('messages.read_more') }}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    </div>
</article>
