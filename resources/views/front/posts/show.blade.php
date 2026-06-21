@extends('front.layouts.app')
@section('title', $post->getTranslation('title', app()->getLocale()) . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')
    @php $locale = app()->getLocale(); @endphp

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.news'),
        'title'    => $post->getTranslation('title', $locale),
        'crumbs'   => [
            ['label' => __('messages.news'), 'url' => route('posts.index')],
            ['label' => Str::limit($post->getTranslation('title', $locale), 40)],
        ],
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row">

                {{-- ── Content ── --}}
                <div class="col-lg-8 order-lg-1 order-2">

                    <div style="border-radius:var(--radius-lg);overflow:hidden;margin-bottom:1.6rem;aspect-ratio:16/8;background:var(--stone-50)">
                        @if($post->hasMedia('cover'))
                            <img src="{{ $post->cover_url }}" alt="{{ $post->getTranslation('title', $locale) }}" style="width:100%;height:100%;object-fit:cover;display:block">
                        @else
                            <div class="mt-post-img-fallback" style="height:100%">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                            </div>
                        @endif
                    </div>

                    <div class="mt-post-meta" style="margin-bottom:1.2rem;font-size:.85rem">
                        @if($post->author)
                            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>{{ $post->author->name }}</span>
                        @endif
                        <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>{{ $post->published_at?->format('d M Y') }}</span>
                        <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>{{ number_format($post->views_count) }}</span>
                        @if($post->reading_time)
                            <span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>{{ $post->reading_time }} {{ __('messages.min_read') }}</span>
                        @endif
                    </div>

                    <h1 class="mt-heading" style="font-size:1.6rem;margin-bottom:1rem">{{ $post->getTranslation('title', $locale) }}</h1>

                    <div style="line-height:2;font-size:1rem;color:var(--stone-700)">
                        {!! $post->getTranslation('content', $locale) !!}
                    </div>

                    @if($post->getMedia('gallery')->count())
                        <div style="margin-top:2.2rem">
                            <h4 class="mt-heading" style="font-size:1.1rem;margin-bottom:1rem">{{ __('messages.gallery') ?? 'Gallery' }}</h4>
                            <div class="row g-3">
                                @foreach($post->getMedia('gallery') as $img)
                                    <div class="col-md-4">
                                        <a href="{{ $img->getUrl() }}" target="_blank" style="display:block;border-radius:12px;overflow:hidden;aspect-ratio:4/3">
                                            <img src="{{ $img->getUrl('thumb') }}" style="width:100%;height:100%;object-fit:cover">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div style="margin-top:2.2rem;padding-top:1.4rem;border-top:1px solid var(--stone-100);display:flex;align-items:center;gap:.8rem">
                        <span style="font-size:.85rem;color:var(--stone-500);font-weight:600">{{ __('messages.share') ?? 'Share' }}:</span>
                        <a class="mt-icon-btn" style="background:var(--stone-50);color:var(--ink);border-color:var(--stone-100)" href="https://wa.me/?text={{ urlencode($post->getTranslation('title', $locale) . ' ' . url()->current()) }}" target="_blank">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="17" height="17"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.37 5.07L2 22l5.07-1.33A9.96 9.96 0 0 0 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z" opacity="0"/><path d="M17.47 14.38c-.28-.14-1.64-.81-1.9-.9-.25-.1-.44-.14-.62.14-.18.27-.71.9-.87 1.08-.16.18-.32.2-.6.07-.27-.14-1.16-.43-2.2-1.36-.82-.73-1.36-1.62-1.53-1.9-.16-.27-.02-.42.12-.56.13-.13.27-.32.41-.49.14-.16.18-.27.27-.46.09-.18.05-.34-.02-.48-.07-.14-.62-1.5-.86-2.05-.22-.54-.45-.46-.62-.47-.16 0-.34-.01-.53-.01-.18 0-.48.07-.73.34-.25.27-.96.94-.96 2.29 0 1.35.98 2.66 1.12 2.84.14.18 1.93 2.95 4.68 4.13.65.28 1.16.45 1.56.58.66.21 1.25.18 1.72.11.52-.08 1.64-.67 1.87-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.18-.53-.32z"/></svg>
                        </a>
                        <a class="mt-icon-btn" style="background:var(--stone-50);color:var(--ink);border-color:var(--stone-100)" href="https://t.me/share/url?url={{ urlencode(url()->current()) }}" target="_blank">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="17" height="17"><path d="M21.9 4.6 18.7 19.8c-.2 1.1-.9 1.4-1.8.9l-4.9-3.6-2.4 2.3c-.3.3-.5.5-1 .5l.3-5 9.2-8.3c.4-.4-.1-.6-.6-.2L6.6 13l-4.9-1.5c-1.1-.3-1.1-1.1.2-1.6L20.5 3.4c.9-.3 1.7.2 1.4 1.2z"/></svg>
                        </a>
                        <a class="mt-icon-btn" style="background:var(--stone-50);color:var(--ink);border-color:var(--stone-100)" href="https://www.linkedin.com/shareArticle?url={{ urlencode(url()->current()) }}" target="_blank">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="17" height="17"><path d="M6.94 5a2 2 0 1 1 0 4 2 2 0 0 1 0-4zM3.5 8.98h6.88V21H3.5zM14.5 8.98h6.6v1.68h.1c.92-1.68 3.16-1.68 4.06 0 1 1.83.8 4.16.8 6.06V21h-6.88v-5.5c0-1.3-.02-3-1.84-3s-2.12 1.4-2.12 2.9V21h-6.7z"/></svg>
                        </a>
                    </div>

                    @if($relatedPosts->count())
                        <div style="margin-top:2.6rem">
                            <h4 class="mt-heading" style="font-size:1.2rem;margin-bottom:1.2rem">{{ __('messages.related') ?? 'Related' }}</h4>
                            <div class="row g-4">
                                @foreach($relatedPosts as $related)
                                    <div class="col-md-4">
                                        @include('front.components.post-card', ['post' => $related])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ── Sidebar ── --}}
                <div class="col-lg-4 order-lg-2 order-1 pt-10 pt-lg-0">
                    <div style="display:grid;gap:1.6rem">
                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">{{ __('messages.search') }}</h3>
                            <form action="{{ route('search') }}" method="GET" style="display:flex;gap:.5rem">
                                <input class="form-control" type="search" name="q" placeholder="{{ __('messages.search_placeholder') }}">
                                <button type="submit" class="mt-btn mt-btn-primary mt-btn-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </button>
                            </form>
                        </div>

                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">{{ __('messages.latest_news') }}</h3>
                            <div style="display:grid;gap:1rem">
                                @foreach(\App\Models\Post::published()->with('media')->where('id','!=',$post->id)->limit(4)->get() as $recentPost)
                                    <a href="{{ route('posts.show', $recentPost->getTranslation('slug', $locale)) }}" style="display:flex;gap:.8rem;text-decoration:none;align-items:center">
                                        <span style="width:64px;height:56px;flex-shrink:0;border-radius:10px;overflow:hidden;display:block;background:var(--stone-50)">
                                            @if($recentPost->hasMedia('cover'))
                                                <img src="{{ $recentPost->thumb_url }}" alt="" style="width:100%;height:100%;object-fit:cover">
                                            @endif
                                        </span>
                                        <span>
                                            <strong style="display:block;font-size:.85rem;color:var(--ink);line-height:1.4">
                                                {{ Str::limit($recentPost->getTranslation('title', $locale), 50) }}
                                            </strong>
                                            <span style="font-size:.72rem;color:var(--stone-500)">
                                                {{ $recentPost->published_at?->format('d M Y') }}
                                            </span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
