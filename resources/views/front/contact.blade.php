@extends('front.layouts.app')
@section('title', __('messages.contact') . ' — ' . \App\Models\Setting::get('site_name'))

@php
    $locale    = app()->getLocale();
    $phone     = display_phone(\App\Models\Setting::get('site_phone'));
    $email     = \App\Models\Setting::get('site_email');
    $address   = \App\Models\Setting::get('site_address');
    $hours     = \App\Models\Setting::get('site_working_hours');
    $mapLat    = (float) (\App\Models\Setting::get('site_map_lat') ?: 36.6736); // Zanjan, Iran
    $mapLng    = (float) (\App\Models\Setting::get('site_map_lng') ?: 48.4787);
    $whatsappNumber = \App\Models\Setting::get('social_whatsapp') ?: $phone;
    $whatsapp  = $whatsappNumber ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsappNumber) : null;
@endphp

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.contact'),
        'title'    => __('messages.contact_us_title') ?? __('messages.contact'),
        'desc'     => __('messages.contact_us_desc'),
    ])

    {{-- ── Contact info cards ── --}}
    <div class="mt-section mt-section--tight">
        <div class="mt-container">
            <div class="row g-4 mb-5">

                @if($phone)
                    <div class="col-lg-3 col-sm-6">
                        <div class="mt-contact-card">
                            <span class="mt-contact-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </span>
                            <h4>{{ __('messages.phone') }}</h4>
                            <a href="tel:{{ $phone }}">{{ $phone }}</a>
                            @if($hours) <span>{{ $hours }}</span> @endif
                        </div>
                    </div>
                @endif

                @if($email)
                    <div class="col-lg-3 col-sm-6">
                        <div class="mt-contact-card">
                            <span class="mt-contact-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
                            </span>
                            <h4>{{ __('messages.email') }}</h4>
                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                        </div>
                    </div>
                @endif

                @if($whatsapp)
                    <div class="col-lg-3 col-sm-6">
                        <div class="mt-contact-card">
                            <span class="mt-contact-card-ico">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.37 5.07L2 22l5.07-1.33A9.96 9.96 0 0 0 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm4.95 14.38c-.2.57-1.18 1.07-1.63 1.12-.41.04-.93.06-1.5-.09-.35-.09-.79-.22-1.36-.47-2.4-1.03-3.96-3.45-4.08-3.61-.12-.16-.97-1.29-.97-2.46 0-1.17.61-1.74.83-1.98.22-.24.48-.3.64-.3l.46.01c.15 0 .35-.06.55.42l.7 1.82c.07.17.03.37-.08.53l-.36.48c-.12.17-.25.34-.11.67.14.33.63 1.05 1.35 1.7.93.83 1.71 1.09 2.04 1.21.33.12.52.1.71-.06.19-.16.83-.97 1.05-1.3.22-.33.44-.28.74-.17l2.08.98c.3.14.5.21.57.34.07.12.07.69-.13 1.26z"/></svg>
                            </span>
                            <h4>WhatsApp</h4>
                            <a href="{{ $whatsapp }}" target="_blank" rel="noopener">{{ __('messages.send_message') ?? 'Send message' }}</a>
                        </div>
                    </div>
                @endif

                @if($address)
                    <div class="col-lg-3 col-sm-6">
                        <div class="mt-contact-card">
                            <span class="mt-contact-card-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            <h4>{{ __('messages.address') }}</h4>
                            <span>{{ $address }}</span>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── Form + Map ── --}}
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="sidebar-widget" style="padding:2rem">
                        <h2 class="mt-heading" style="font-size:1.35rem;margin-bottom:.4rem">
                            {{ __('messages.send_message') ?? 'Send a Message' }}
                        </h2>
                        <p class="mt-lede" style="font-size:.9rem;margin-bottom:1.6rem">
                            {{ __('messages.contact_form_desc') ?? '' }}
                        </p>

                        @include('front.components.flash')

                        <form action="{{ route('contact.store') }}" method="POST" style="display:grid;gap:1rem">
                            @csrf
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                                <div>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                           placeholder="{{ __('messages.name') }} *"
                                           class="form-control" required>
                                </div>
                                <div>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           placeholder="{{ __('messages.email') }} *"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                       placeholder="{{ __('messages.phone') }}"
                                       class="form-control">
                                <input type="text" name="company" value="{{ old('company') }}"
                                       placeholder="{{ __('messages.company') ?? 'Company' }}"
                                       class="form-control">
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                                <input type="text" name="country" value="{{ old('country') }}"
                                       placeholder="{{ __('admin.country') }}"
                                       class="form-control" maxlength="5">
                                <input type="text" name="subject" value="{{ old('subject') }}"
                                       placeholder="{{ __('messages.subject') ?? 'Subject' }}"
                                       class="form-control">
                            </div>
                            <textarea name="message" rows="5"
                                      placeholder="{{ __('messages.message') }} *"
                                      class="form-control" required>{{ old('message') }}</textarea>

                            <button type="submit" class="mt-btn mt-btn-primary" style="justify-self:start">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                {{ __('messages.send_message') ?? 'Send Message' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div id="contact-map" style="border-radius:var(--radius-lg);overflow:hidden;height:100%;min-height:420px;background:var(--stone-50)"></div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/leaflet/leaflet.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/vendor/leaflet.js') }}"></script>
    <script>
        (function () {
            var el = document.getElementById('contact-map');
            if (!el || typeof L === 'undefined') {
                return;
            }

            var lat = {{ $mapLat }};
            var lng = {{ $mapLng }};

            var map = L.map(el, { scrollWheelZoom: false }).setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);
        })();
    </script>
@endpush
