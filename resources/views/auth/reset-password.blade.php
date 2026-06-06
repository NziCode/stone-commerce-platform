@extends('front.layouts.app')
@section('title', 'تغییر رمز عبور')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'تغییر رمز عبور',
    ])

    <div class="login-register-area py-140">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="login-form">
                        <h4 class="login-title">تغییر رمز عبور</h4>

                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="row">
                                <div class="col-12 mb--20">
                                    <label>ایمیل *</label>
                                    <input type="email" name="email"
                                           value="{{ old('email', $request->email) }}"
                                           placeholder="آدرس ایمیل"
                                           required autofocus autocomplete="username">
                                    @error('email')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mb--20">
                                    <label>رمز عبور جدید *</label>
                                    <input type="password" name="password"
                                           placeholder="رمز عبور جدید"
                                           required autocomplete="new-password">
                                    @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mb--20">
                                    <label>تکرار رمز عبور *</label>
                                    <input type="password" name="password_confirmation"
                                           placeholder="تکرار رمز عبور"
                                           required autocomplete="new-password">
                                </div>

                                <div class="col-12 mt-2">
                                    <button type="submit"
                                            class="btn btn-primary btn-secondary-hover">
                                        تغییر رمز عبور
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
