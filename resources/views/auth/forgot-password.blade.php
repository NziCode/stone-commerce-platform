@extends('front.layouts.app')
@section('title', 'فراموشی رمز عبور')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'فراموشی رمز عبور',
    ])

    <div class="login-register-area py-140">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="login-form">
                        <h4 class="login-title">بازیابی رمز عبور</h4>
                        <p class="text-muted mb-6">
                            ایمیل خود را وارد کنید. لینک بازیابی رمز برایتان ارسال می‌شود.
                        </p>

                        @if(session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb--20">
                                    <label>ایمیل *</label>
                                    <input type="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="آدرس ایمیل"
                                           required autofocus>
                                    @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mt-2">
                                    <button type="submit"
                                            class="btn btn-primary btn-secondary-hover">
                                        ارسال لینک بازیابی
                                    </button>
                                </div>

                                <div class="col-12 mt-4">
                                    <a href="{{ route('login') }}" class="text-primary">
                                        <i class="fa fa-arrow-right me-1"></i>
                                        بازگشت به ورود
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
