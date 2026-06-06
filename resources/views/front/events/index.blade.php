@extends('front.layouts.app')
@section('title', 'نمایشگاه‌ها')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'رویدادها',
        'title'    => 'نمایشگاه‌ها',
    ])

    <div class="blog-area py-140">
        <div class="container">

            {{-- در حال برگزاری --}}
            @if($ongoingEvents->count())
                <div class="mb-12">
                    <h3 class="mb-6 text-primary">در حال برگزاری</h3>
                    <div class="row">
                        @foreach($ongoingEvents as $event)
                            @include('front.components.event-card', ['event' => $event])
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- آینده --}}
            @if($upcomingEvents->count())
                <div class="mb-12">
                    <h3 class="mb-6">نمایشگاه‌های آینده</h3>
                    <div class="row">
                        @foreach($upcomingEvents as $event)
                            @include('front.components.event-card', ['event' => $event])
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- پایان یافته --}}
            @if($finishedEvents->count())
                <div class="mb-12">
                    <h3 class="mb-6 text-muted">نمایشگاه‌های گذشته</h3>
                    <div class="row">
                        @foreach($finishedEvents as $event)
                            @include('front.components.event-card', ['event' => $event, 'finished' => true])
                        @endforeach
                    </div>
                    @if($finishedEvents->hasPages())
                        <div class="col-lg-12 pt-8">
                            <div class="pagination-wrap">
                                <nav>
                                    <ul class="pagination pagination-custom justify-content-center">
                                        <li class="page-item {{ $finishedEvents->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $finishedEvents->previousPageUrl() }}">
                                                <i class="ion-ios-arrow-back"></i>
                                            </a>
                                        </li>
                                        @for($i = 1; $i <= $finishedEvents->lastPage(); $i++)
                                            <li class="page-item {{ $finishedEvents->currentPage() === $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $finishedEvents->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ !$finishedEvents->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $finishedEvents->nextPageUrl() }}">
                                                <i class="ion-ios-arrow-forward"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if(!$ongoingEvents->count() && !$upcomingEvents->count() && !$finishedEvents->count())
                <div class="text-center py-10">
                    <i class="fa fa-calendar fa-3x text-muted mb-4 d-block"></i>
                    <h4 class="text-muted">نمایشگاهی ثبت نشده است</h4>
                </div>
            @endif
        </div>
    </div>

@endsection
