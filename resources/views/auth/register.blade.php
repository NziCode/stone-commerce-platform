@extends('front.layouts.app')
@section('title', 'ثبت‌نام')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'ثبت‌نام',
    ])

    <div class="login-register-area py-140">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="login-form">
                            <h4 class="login-title">ایجاد حساب کاربری</h4>
                            <div class="row">

                                <div class="col-md-12 mb--20">
                                    <label>نام کامل *</label>
                                    <input type="text" name="name"
                                           value="{{ old('name') }}"
                                           placeholder="نام و نام خانوادگی"
                                           required autofocus>
                                    @error('name')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb--20">
                                    <label>ایمیل *</label>
                                    <input type="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="آدرس ایمیل"
                                           required autocomplete="username">
                                    @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb--20">
                                    <label>موبایل</label>
                                    <input type="text" name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="شماره موبایل">
                                </div>

                                <div class="col-md-6 mb--20">
                                    <label>شرکت</label>
                                    <input type="text" name="company"
                                           value="{{ old('company') }}"
                                           placeholder="نام شرکت">
                                </div>

                                <div class="col-md-6 mb--20">
                                    <label>کشور</label>
                                    <input type="text" name="country"
                                           value="{{ old('country') }}"
                                           maxlength="5"
                                           placeholder="IR, DE, US...">
                                </div>

                                <div class="col-md-6 mb--20">
                                    <label>زبان پیش‌فرض</label>
                                    <select name="locale" class="input-field w-100"
                                            style="height:48px">
                                        <option value="fa" {{ old('locale') === 'fa' ? 'selected' : '' }}>فارسی</option>
                                        <option value="en" {{ old('locale') === 'en' ? 'selected' : '' }}>English</option>
                                        <option value="ar" {{ old('locale') === 'ar' ? 'selected' : '' }}>العربية</option>
                                        <option value="hi" {{ old('locale') === 'hi' ? 'selected' : '' }}>हिन्दी</option>
                                        <option value="it" {{ old('locale') === 'it' ? 'selected' : '' }}>Italiano</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>رمز عبور *</label>
                                    <input type="password" name="password"
                                           placeholder="رمز عبور (حداقل ۸ کاراکتر)"
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

                                <div class="col-12 mt-2">
                                    <button type="submit"
                                            class="btn btn-primary btn-secondary-hover">
                                        ثبت‌نام
                                    </button>
                                </div>

                                <div class="col-12 mt-4">
                                    <p class="mb-0 text-muted">
                                        قبلاً ثبت‌نام کرده‌اید؟
                                        <a href="{{ route('login') }}" class="text-primary fw-bold">
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
