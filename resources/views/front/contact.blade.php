@extends('front.layouts.app')
@section('title', 'تماس با ما')

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => 'ارتباط با ما',
        'title'    => 'تماس با ما',
    ])

    {{-- Info Boxes --}}
    <div class="banner pt-140">
        <div class="container">
            <div class="row g-lg-9">
                @if(\App\Models\Setting::get('site_phone'))
                    <div class="col-lg-4 col-md-6">
                        <div class="banner-item text-white"
                             data-bg-image="{{ asset('assets/images/banner/inner-bg/1-1.png') }}">
                            <div class="banner-content">
                                <i class="fa fa-phone fa-2x mb-3 d-block"></i>
                                <h3 class="title mb-3">تلفن</h3>
                                <p class="short-desc mb-0">
                                    <a href="tel:{{ \App\Models\Setting::get('site_phone') }}" class="text-white">
                                        {{ \App\Models\Setting::get('site_phone') }}
                                    </a>
                                </p>
                                @if(\App\Models\Setting::get('site_working_hours'))
                                    <p class="short-desc mb-0 mt-2" style="font-size:13px">
                                        {{ \App\Models\Setting::get('site_working_hours') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if(\App\Models\Setting::get('site_email'))
                    <div class="col-lg-4 col-md-6 pt-6 pt-md-0">
                        <div class="banner-item text-white"
                             data-bg-image="{{ asset('assets/images/banner/inner-bg/1-2.png') }}">
                            <div class="banner-content">
                                <i class="fa fa-envelope fa-2x mb-3 d-block"></i>
                                <h3 class="title mb-3">ایمیل</h3>
                                <p class="short-desc mb-0">
                                    <a href="mailto:{{ \App\Models\Setting::get('site_email') }}" class="text-white">
                                        {{ \App\Models\Setting::get('site_email') }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if(\App\Models\Setting::get('site_address'))
                    <div class="col-lg-4 col-md-6 pt-6 pt-lg-0">
                        <div class="banner-item text-white"
                             data-bg-image="{{ asset('assets/images/banner/inner-bg/1-3.png') }}">
                            <div class="banner-content">
                                <i class="fa fa-map-marker fa-2x mb-3 d-block"></i>
                                <h3 class="title mb-3">آدرس</h3>
                                <p class="short-desc mb-0">{{ \App\Models\Setting::get('site_address') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Contact Form + Map --}}
    <div class="contact-form-area pt-130 pb-115">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="contact-form-title mb-3">ارسال پیام</h2>
                    <p class="contact-form-desc mb-0">
                        برای ارتباط با ما فرم زیر را تکمیل کنید. در اسرع وقت پاسخگوی شما خواهیم بود.
                    </p>

                    <form class="contact-form pt-10" action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="group-input">
                            <input type="text" name="name" value="{{ old('name') }}"
                                   placeholder="نام شما *" class="input-field me-6" required>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   placeholder="ایمیل شما *" class="input-field mt-6 mt-sm-0" required>
                        </div>
                        <div class="group-input mt-6">
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   placeholder="تلفن" class="input-field me-6">
                            <input type="text" name="company" value="{{ old('company') }}"
                                   placeholder="شرکت" class="input-field mt-6 mt-sm-0">
                        </div>
                        <div class="group-input mt-6">
                            <input type="text" name="country" value="{{ old('country') }}"
                                   placeholder="کشور (مثال: IR)" class="input-field me-6" maxlength="5">
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                   placeholder="موضوع" class="input-field mt-6 mt-sm-0">
                        </div>
                        <div class="form-field mt-6">
                        <textarea name="message" placeholder="پیام شما *"
                                  class="textarea-field" required>{{ old('message') }}</textarea>
                        </div>
                        @if($errors->any())
                            <div class="alert alert-danger mt-4">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="button-wrap mt-8">
                            <button type="submit"
                                    class="btn btn-custom btn-secondary btn-primary-hover">
                                ارسال پیام
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-6 ps-lg-10">
                    <div class="map-with-pattern pt-9">
                        @if(\App\Models\Setting::get('site_google_map_embed'))
                            {!! \App\Models\Setting::get('site_google_map_embed') !!}
                        @else
                            <iframe class="map-size"
                                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d100000!2d51.338!3d35.6892!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sfa!2sir!4v1607512676761"
                                    allowfullscreen="" loading="lazy">
                            </iframe>
                        @endif
                        <div class="contact-pattern">
                            <img src="{{ asset('assets/images/contact/pattern.png') }}" alt="Pattern">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
