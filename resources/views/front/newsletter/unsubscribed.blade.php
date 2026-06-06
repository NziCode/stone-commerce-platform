@extends('front.layouts.app')
@section('title', 'لغو اشتراک')

@section('content')

    @include('front.components.breadcrumb', [
        'title' => 'لغو اشتراک از خبرنامه',
    ])

    <div class="py-140">
        <div class="container">
            <div class="text-center py-10">
                <div class="counter-item d-inline-block p-8 mb-6">
                    <i class="fa fa-check-circle fa-4x text-success mb-4 d-block"></i>
                    <h3 class="mb-3">اشتراک شما لغو شد</h3>
                    <p class="text-muted mb-6">
                        با موفقیت از خبرنامه لغو اشتراک کردید.
                    </p>
                    <a href="{{ route('home') }}"
                       class="btn btn-custom btn-secondary btn-primary-hover">
                        بازگشت به خانه
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
