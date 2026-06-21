@extends('front.layouts.app')
@section('title', __('messages.news') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')
    @php $locale = app()->getLocale(); @endphp

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.news'),
        'title'    => __('messages.latest_news'),
    ])

    <div class="mt-section">
        <div class="mt-container">
            <div class="row">

                {{-- ── News list ── --}}
                <div class="col-lg-8 order-lg-1 order-2">
                    @forelse($posts as $post)
                        <div class="{{ !$loop->first ? 'mt-4' : '' }}" style="margin-bottom:1.5rem">
                            @include('front.components.post-card', ['post' => $post])
                        </div>
                    @empty
                        <div class="no-products-found py-10 text-center">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="48" height="48" style="margin:0 auto 1rem;color:var(--stone-200)"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>
                            <h4 class="text-muted">{{ __('messages.no_products') }}</h4>
                        </div>
                    @endforelse

                    @if($posts->hasPages())
                        <div class="pt-6">
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $posts->previousPageUrl() }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M15 18l-6-6 6-6"/></svg>
                                        </a>
                                    </li>
                                    @for($i = 1; $i <= $posts->lastPage(); $i++)
                                        <li class="page-item {{ $posts->currentPage() === $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item {{ !$posts->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $posts->nextPageUrl() }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>

                {{-- ── Sidebar ── --}}
                <div class="col-lg-4 order-lg-2 order-1 pt-10 pt-lg-0">
                    <div style="display:grid;gap:1.6rem">

                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">{{ __('messages.search') }}</h3>
                            <form action="{{ route('search') }}" method="GET" style="display:flex;gap:.5rem">
                                <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.search_placeholder') }}">
                                <button type="submit" class="mt-btn mt-btn-primary mt-btn-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="17" height="17"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </button>
                            </form>
                        </div>

                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">{{ __('messages.latest_news') }}</h3>
                            <div style="display:grid;gap:1rem">
                                @foreach(\App\Models\Post::published()->with('media')->limit(4)->get() as $recentPost)
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

                        <div class="sidebar-widget">
                            <h3 class="sidebar-title">{{ __('messages.exhibitions') }}</h3>
                            <div style="display:grid;gap:.9rem">
                                @forelse(\App\Models\Event::upcoming()->limit(3)->get() as $event)
                                    <a href="{{ route('events.show', $event->getTranslation('slug', $locale)) }}" style="text-decoration:none;display:block">
                                        <strong style="display:block;font-size:.85rem;color:var(--ink);line-height:1.4">
                                            {{ Str::limit($event->getTranslation('title', $locale), 50) }}
                                        </strong>
                                        <span style="font-size:.72rem;color:var(--brand)">
                                            {{ $event->starts_at?->format('d M Y') }}
                                        </span>
                                    </a>
                                @empty
                                    <p class="text-muted mb-0" style="font-size:.85rem">—</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
