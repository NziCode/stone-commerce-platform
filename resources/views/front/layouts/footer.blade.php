@php
    $footerMenu = \App\Models\Menu::getByLocation('footer');
    $siteName = \App\Models\Setting::get('site_name', config('app.name'));
    $sitePhone = \App\Models\Setting::get('site_phone');
    $siteEmail = \App\Models\Setting::get('site_email');
    $siteAddress = \App\Models\Setting::get('site_address');
    $social = [
        'facebook'  => \App\Models\Setting::get('social_facebook'),
        'twitter'   => \App\Models\Setting::get('social_twitter'),
        'instagram' => \App\Models\Setting::get('social_instagram'),
        'linkedin'  => \App\Models\Setting::get('social_linkedin'),
        'youtube'   => \App\Models\Setting::get('social_youtube'),
    ];
@endphp

{{-- Newsletter --}}
<div class="newsletter-area pt-9 pb-8"
     data-bg-image="{{ asset('assets/images/newsletter/bg/1-1-1920x198.png') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="newsletter-item text-white">
                    <div class="newsletter-content">
                        <h2 class="title text-lg-end text-center mb-0">عضویت در خبرنامه</h2>
                    </div>
                    <div class="newsletter-form_wrap align-self-center">
                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="form-field">
                                <input class="input-field" type="email" name="email"
                                       placeholder="ایمیل خود را وارد کنید">
                            </div>
                            <div class="form-btn_wrap">
                                <button class="btn btn-secondary btn-secondary-hover btn-lg rounded-0" type="submit">
                                    عضو شو
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Footer Main --}}
<div class="footer-area">
    <div class="footer-top pt-100 pb-80"
         data-bg-image="{{ asset('assets/images/footer/bg/1-1-1920x454.png') }}">
        <div class="container">
            <div class="row">

                {{-- About Widget --}}
                <div class="col-xl-3 col-lg-3">
                    <div class="widget-item text-hawkes-blue">
                        <div class="footer-logo pb-5">
                            <a href="{{ route('home') }}">
                                @if(\App\Models\Setting::get('site_logo'))
                                    <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="{{ $siteName }}">
                                @else
                                    <img src="{{ asset('assets/images/logo/white.svg') }}" alt="{{ $siteName }}">
                                @endif
                            </a>
                        </div>
                        <p class="short-desc font-size-16 mb-5">
                            {{ \App\Models\Setting::get('site_tagline') }}
                        </p>
                        @if($sitePhone)
                            <div class="inquary">
                                <h5 class="text-primary">تماس با ما</h5>
                                <a href="tel:{{ $sitePhone }}">{{ $sitePhone }}</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Menu Widgets --}}
                @if($footerMenu)
                    @foreach($footerMenu->items->take(2) as $section)
                        <div class="col-xl-3 col-lg-2 col-sm-6 pl-xl-80 pt-8 pt-lg-0">
                            <div class="widget-item">
                                <h3 class="heading text-white mb-6">
                                    {{ $section->getTranslation('label', app()->getLocale()) }}
                                </h3>
                                <ul class="widget-list-item text-hawkes-blue">
                                    @foreach($section->children as $child)
                                        <li>
                                            <a href="{{ $child->href }}">
                                                {{ $child->getTranslation('label', app()->getLocale()) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-xl-3 col-lg-2 col-sm-6 pl-xl-80 pt-8 pt-lg-0">
                        <div class="widget-item">
                            <h3 class="heading text-white mb-6">لینک‌های سریع</h3>
                            <ul class="widget-list-item text-hawkes-blue">
                                <li><a href="{{ route('home') }}">خانه</a></li>
                                <li><a href="{{ route('products.index') }}">محصولات</a></li>
                                <li><a href="{{ route('posts.index') }}">اخبار</a></li>
                                <li><a href="{{ route('events.index') }}">نمایشگاه‌ها</a></li>
                                <li><a href="{{ route('contact') }}">تماس با ما</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-sm-6 ps-lg-10 pt-8 pt-lg-0">
                        <div class="widget-item">
                            <h3 class="heading text-white mb-6">حساب کاربری</h3>
                            <ul class="widget-list-item text-hawkes-blue">
                                @auth
                                    <li><a href="{{ route('profile.index') }}">پروفایل</a></li>
                                    <li><a href="{{ route('orders.index') }}">سفارشات</a></li>
                                    <li><a href="{{ route('wishlist.index') }}">علاقه‌مندی‌ها</a></li>
                                    <li><a href="{{ route('cart.index') }}">سبد خرید</a></li>
                                @else
                                    <li><a href="{{ route('login') }}">ورود</a></li>
                                    <li><a href="{{ route('register') }}">ثبت‌نام</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Contact Info --}}
                <div class="col-xl-3 col-lg-4 pt-8 pt-lg-0">
                    <div class="widget-item">
                        <h3 class="heading text-white mb-6">اطلاعات تماس</h3>
                        <div class="widget-list-item text-hawkes-blue">
                            @if($siteAddress)
                                <div class="widget-address pb-5">
                                    <p class="mb-1">{{ $siteAddress }}</p>
                                    @if($sitePhone)
                                        <span>{{ $sitePhone }}</span>
                                    @endif
                                </div>
                            @endif
                            @if($siteEmail)
                                <div class="widget-address">
                                    <p class="mb-1">
                                        <a href="mailto:{{ $siteEmail }}" class="text-hawkes-blue">
                                            {{ $siteEmail }}
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Footer Bottom --}}
    <div class="footer-bottom py-3 text-hawkes-blue" data-bg-color="#00225a">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-4">
                    <ul class="social-link">
                        @if($social['facebook'])
                            <li class="facebook">
                                <a href="{{ $social['facebook'] }}" target="_blank">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                        @endif
                        @if($social['twitter'])
                            <li class="twitter">
                                <a href="{{ $social['twitter'] }}" target="_blank">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                        @endif
                        @if($social['instagram'])
                            <li class="instagram">
                                <a href="{{ $social['instagram'] }}" target="_blank">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </li>
                        @endif
                        @if($social['linkedin'])
                            <li>
                                <a href="{{ $social['linkedin'] }}" target="_blank">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                            </li>
                        @endif
                        @if($social['youtube'])
                            <li>
                                <a href="{{ $social['youtube'] }}" target="_blank">
                                    <i class="fa fa-youtube"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-6 col-sm-8 align-self-center">
                    <div class="copyright">
                        <span class="copyright-text">
                            © {{ date('Y') }} {{ $siteName }} — تمامی حقوق محفوظ است.
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
