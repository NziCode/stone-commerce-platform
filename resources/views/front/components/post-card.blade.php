<article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
    <a href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}"
       class="block aspect-video overflow-hidden bg-gray-100">
        <img src="{{ $post->cover_url }}"
             alt="{{ $post->getTranslation('title', app()->getLocale()) }}"
             class="w-full h-full object-cover hover:scale-105 transition duration-300">
    </a>
    <div class="p-4">
        <p class="text-xs text-gray-500 mb-2">
            {{ $post->published_at?->diffForHumans() }}
            @if($post->reading_time)
                · {{ $post->reading_time }} دقیقه مطالعه
            @endif
        </p>
        <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">
            <a href="{{ route('posts.show', $post->getTranslation('slug', app()->getLocale())) }}"
               class="hover:text-amber-600 transition">
                {{ $post->getTranslation('title', app()->getLocale()) }}
            </a>
        </h3>
        @if($post->getTranslation('excerpt', app()->getLocale()))
            <p class="text-sm text-gray-600 line-clamp-3">
                {{ $post->getTranslation('excerpt', app()->getLocale()) }}
            </p>
        @endif
    </div>
</article>
