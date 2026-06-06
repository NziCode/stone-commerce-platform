@extends('front.layouts.app')
@section('title', 'تأیید رمز عبور')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'تأیید هویت',
    ])

    <div class="login-register-area py-140">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="login-form">
                        <h4 class="login-title">تأیید رمز عبور</h4>
                        <p class="text-muted mb-6">
                            برای ادامه لطفاً رمز عبور خود را وارد کنید.
                        </p>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb--20">
                                    <label>رمز عبور *</label>
                                    <input type="password" name="password"
                                           placeholder="رمز عبور"
                                           required autocomplete="current-password">
                                    @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mt-2">
                                    <button type="submit"
                                            class="btn btn-primary btn-secondary-hover">
                                        تأیید
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
