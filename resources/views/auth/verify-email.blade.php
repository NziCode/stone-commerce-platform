@extends('front.layouts.app')
@section('title', 'تأیید ایمیل')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'تأیید ایمیل',
    ])

    <div class="login-register-area py-140">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="login-form">
                        <i class="fa fa-envelope fa-3x text-primary mb-4 d-block"></i>
                        <h4 class="login-title">ایمیل خود را تأیید کنید</h4>
                        <p class="text-muted mb-6">
                            یک لینک تأیید به آدرس ایمیل شما ارسال شد.
                            لطفاً ایمیل خود را بررسی کنید.
                        </p>

                        @if(session('status') == 'verification-link-sent')
                            <div class="alert alert-success mb-4">
                                لینک تأیید جدید ارسال شد.
                            </div>
                        @endif

                        <div class="d-flex gap-3 justify-content-center">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-primary btn-secondary-hover">
                                    ارسال مجدد لینک
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-outline-secondary rounded-0">
                                    خروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
