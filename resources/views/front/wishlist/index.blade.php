@extends('front.layouts.app')
@section('title', 'علاقه‌مندی‌ها')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'علاقه‌مندی‌ها',
    ])

    <div class="py-140">
        <div class="container">
            @if($wishlists->count())
                <div class="row">
                    @foreach($wishlists as $wishlist)
                        @if($wishlist->product)
                            @include('front.components.product-card', ['product' => $wishlist->product])
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <i class="fa fa-heart-o fa-4x text-muted mb-6 d-block"></i>
                    <h3 class="text-muted mb-4">علاقه‌مندی‌ها خالی است</h3>
                    <a href="{{ route('products.index') }}"
                       class="btn btn-custom btn-secondary btn-primary-hover">
                        مشاهده محصولات
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
