@extends('front.layouts.app')
@section('title', 'پروفایل من')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'حساب کاربری',
        'title'    => 'پروفایل من',
    ])

    <div class="py-140">
        <div class="container">
            <div class="row">

                {{-- Sidebar پروفایل --}}
                <div class="col-lg-3 mb-8 mb-lg-0">
                    <div class="sidebar-wrap">
                        <div class="sidebar-single-item p-6 text-center mb-6" style="background:#f4f8ff">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 class="rounded-circle mb-3"
                                 style="width:80px;height:80px;object-fit:cover">
                            <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                            <p class="text-muted mb-0" style="font-size:13px">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="sidebar-single-item" style="background:#f4f8ff">
                            <ul class="list-unstyled mb-0">
                                <li class="border-bottom">
                                    <a href="{{ route('profile.index') }}"
                                       class="d-block p-3 text-dark">
                                        <i class="fa fa-user me-2 text-primary"></i> پروفایل
                                    </a>
                                </li>
                                <li class="border-bottom">
                                    <a href="{{ route('orders.index') }}"
                                       class="d-block p-3 text-dark">
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> سفارشات
                                    </a>
                                </li>
                                <li class="border-bottom">
                                    <a href="{{ route('wishlist.index') }}"
                                       class="d-block p-3 text-dark">
                                        <i class="fa fa-heart me-2 text-primary"></i> علاقه‌مندی‌ها
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="d-block p-3 text-danger w-100 text-start"
                                                style="background:none;border:none">
                                            <i class="fa fa-sign-out me-2"></i> خروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- محتوا --}}
                <div class="col-lg-9">

                    {{-- ویرایش پروفایل --}}
                    <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                        <h4 class="mb-6">اطلاعات شخصی</h4>
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">نام *</label>
                                    <input type="text" name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="input-field w-100" required>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">ایمیل</label>
                                    <input type="email" value="{{ $user->email }}"
                                           class="input-field w-100" disabled
                                           style="background:#e9ecef">
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">موبایل</label>
                                    <input type="text" name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           class="input-field w-100">
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">شرکت</label>
                                    <input type="text" name="company"
                                           value="{{ old('company', $user->company) }}"
                                           class="input-field w-100">
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label class="form-label">کشور</label>
                                    <input type="text" name="country"
                                           value="{{ old('country', $user->country) }}"
                                           maxlength="5" placeholder="IR, DE, US..."
                                           class="input-field w-100">
                                </div>
                            </div>
                            <button type="submit"
                                    class="btn btn-custom btn-secondary btn-primary-hover">
                                ذخیره تغییرات
                            </button>
                        </form>
                    </div>

                    {{-- تغییر رمز --}}
                    <div class="sidebar-single-item p-6 mb-6" style="background:#f4f8ff">
                        <h4 class="mb-6">تغییر رمز عبور</h4>
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-sm-4 mb-4">
                                    <label class="form-label">رمز فعلی *</label>
                                    <input type="password" name="current_password"
                                           class="input-field w-100" required>
                                    @error('current_password')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label class="form-label">رمز جدید *</label>
                                    <input type="password" name="password"
                                           class="input-field w-100" required>
                                    @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label class="form-label">تکرار رمز جدید *</label>
                                    <input type="password" name="password_confirmation"
                                           class="input-field w-100" required>
                                </div>
                            </div>
                            <button type="submit"
                                    class="btn btn-custom btn-secondary btn-primary-hover">
                                تغییر رمز
                            </button>
                        </form>
                    </div>

                    {{-- آخرین سفارشات --}}
                    @if($orders->count())
                        <div class="sidebar-single-item p-6" style="background:#f4f8ff">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="mb-0">آخرین سفارشات</h4>
                                <a href="{{ route('orders.index') }}" class="btn btn-link p-0">
                                    مشاهده همه
                                </a>
                            </div>
                            <table class="table table-sm table-bordered mb-0">
                                <thead style="background:#00225a;color:white">
                                <tr>
                                    <th>شماره</th>
                                    <th>مبلغ</th>
                                    <th>وضعیت</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->formatted_total }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}"
                                               class="btn btn-sm btn-outline-secondary rounded-0">
                                                جزئیات
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
