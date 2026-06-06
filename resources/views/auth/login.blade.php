@extends('front.layouts.app')
@section('title', 'ورود به حساب کاربری')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'ورود و ثبت‌نام',
    ])

    <div class="login-register-area py-140">
        <div class="container">
            <div class="row">

                {{-- فرم ورود --}}
                <div class="col-lg-6 pb-6 pb-lg-0">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="login-form">
                            <h4 class="login-title">ورود به حساب</h4>
                            <div class="row">

                                <div class="col-md-12 col-12">
                                    <label>ایمیل *</label>
                                    <input type="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="ایمیل خود را وارد کنید"
                                           required autofocus autocomplete="username">
                                    @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mb--20">
                                    <label>رمز عبور *</label>
                                    <input type="password" name="password"
                                           placeholder="رمز عبور"
                                           required autocomplete="current-password">
                                    @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-8">
                                    <div class="check-box">
                                        <input class="checkbox me-2" type="checkbox"
                                               name="remember" id="remember_me">
                                        <label for="remember_me">مرا به خاطر بسپار</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="forgotton-password_info mt-2 mt-md-0">
                                        @if(Route::has('password.request'))
                                            <a href="{{ route('password.request') }}">
                                                فراموشی رمز؟
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <button type="submit"
                                            class="btn btn-primary btn-secondary-hover">
                                        ورود
                                    </button>
                                </div>

                                <div class="col-12 mt-4">
                                    <p class="mb-0 text-muted">
                                        حساب ندارید؟
                                        <a href="{{ route('register') }}" class="text-primary">
                                            ثبت‌نام کنید
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- فرم ثبت‌نام --}}
                <div class="col-lg-6">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="login-form">
                            <h4 class="login-title">ثبت‌نام</h4>
                            <div class="row">

                                <div class="col-md-6 col-12 mb--20">
                                    <label>نام کامل *</label>
                                    <input type="text" name="name"
                                           value="{{ old('name') }}"
                                           placeholder="نام و نام خانوادگی"
                                           required>
                                    @error('name')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-12 mb--20">
                                    <label>شماره موبایل</label>
                                    <input type="text" name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="شماره موبایل">
                                </div>

                                <div class="col-md-12">
                                    <label>ایمیل *</label>
                                    <input type="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="آدرس ایمیل"
                                           required autocomplete="username">
                                    @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>رمز عبور *</label>
                                    <input type="password" name="password"
                                           placeholder="رمز عبور"
                                           required autocomplete="new-password">
                                    @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>تکرار رمز عبور *</label>
                                    <input type="password" name="password_confirmation"
                                           placeholder="تکرار رمز عبور"
                                           required autocomplete="new-password">
                                </div>

                                <div class="col-12">
                                    <button type="submit"
                                            class="btn btn-primary btn-secondary-hover">
                                        ثبت‌نام
                                    </button>
                                </div>

                                <div class="col-12 mt-4">
                                    <p class="mb-0 text-muted">
                                        قبلاً ثبت‌نام کرده‌اید؟
                                        <a href="{{ route('login') }}" class="text-primary">
                                            وارد شوید
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
