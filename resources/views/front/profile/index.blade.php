@extends('front.layouts.app')
@section('title', __('messages.profile') . ' — ' . \App\Models\Setting::get('site_name'))

@section('content')

    @include('front.components.breadcrumb', [
        'subtitle' => __('messages.account'),
        'title'    => __('messages.profile'),
    ])

    @include('front.components.flash')

    <div class="mt-section">
        <div class="mt-container">
            <div class="row">

                {{-- Sidebar nav --}}
                <div class="col-lg-3 mb-8 mb-lg-0">
                    <div class="sidebar-widget" style="padding:0;overflow:hidden">
                        <div style="background:linear-gradient(160deg,var(--ink),var(--ink-2));padding:1.6rem 1.4rem;text-align:center">
                            <div style="width:72px;height:72px;border-radius:50%;overflow:hidden;margin:0 auto .8rem;background:var(--stone-100)">
                                <img src="{{ auth()->user()->avatar_url }}" style="width:100%;height:100%;object-fit:cover" alt="">
                            </div>
                            <strong style="display:block;color:#fff;font-size:.95rem">{{ auth()->user()->name }}</strong>
                            <span style="display:block;font-size:.74rem;color:rgba(255,255,255,.65)">{{ auth()->user()->email }}</span>
                        </div>
                        <nav style="padding:.5rem">
                            @foreach([
                                ['route' => 'profile.index', 'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>', 'label' => __('messages.profile')],
                                ['route' => 'orders.index', 'icon' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/>', 'label' => __('messages.orders')],
                                ['route' => 'wishlist.index', 'icon' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>', 'label' => __('messages.wishlist')],
                            ] as $link)
                                <a href="{{ route($link['route']) }}" style="display:flex;align-items:center;gap:.7rem;padding:.75rem 1rem;border-radius:10px;text-decoration:none;color:{{ Route::is($link['route']) ? '#fff' : 'var(--ink)' }};font-weight:600;font-size:.88rem;background:{{ Route::is($link['route']) ? 'linear-gradient(135deg,var(--brand),var(--brand-2))' : 'transparent' }};margin-bottom:.3rem">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">{!! $link['icon'] !!}</svg>
                                    {{ $link['label'] }}
                                </a>
                            @endforeach
                            <form method="POST" action="{{ route('logout') }}" style="margin-top:.3rem">
                                @csrf
                                <button type="submit" style="display:flex;align-items:center;gap:.7rem;padding:.75rem 1rem;border-radius:10px;background:none;border:none;color:var(--bad);font-weight:600;font-size:.88rem;width:100%;cursor:pointer">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                                    {{ __('messages.logout') }}
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                <div class="col-lg-9">

                    {{-- Personal info --}}
                    <div class="sidebar-widget" style="margin-bottom:1.4rem">
                        <h4 class="sidebar-title">{{ __('messages.personal_info') ?? 'Personal Info' }}</h4>
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.name') }} *</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') border-danger @enderror" required>
                                    @error('name') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.email') }}</label>
                                    <input type="email" value="{{ $user->email }}" class="form-control" disabled style="background:var(--stone-50);opacity:.7">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.phone') }}</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.company') ?? 'Company' }}</label>
                                    <input type="text" name="company" value="{{ old('company', $user->company) }}" class="form-control">
                                </div>
                                <div class="col-sm-6 mb-1">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('admin.country') }}</label>
                                    <input type="text" name="country" value="{{ old('country', $user->country) }}" maxlength="5" placeholder="IR, DE, US…" class="form-control">
                                </div>
                            </div>
                            <div style="margin-top:1rem">
                                <button type="submit" class="mt-btn mt-btn-primary">{{ __('messages.save_changes') ?? 'Save Changes' }}</button>
                            </div>
                        </form>
                    </div>

                    {{-- Change password --}}
                    <div class="sidebar-widget" style="margin-bottom:1.4rem">
                        <h4 class="sidebar-title">{{ __('messages.change_password') ?? 'Change Password' }}</h4>
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="row">
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.current_password') ?? 'Current Password' }} *</label>
                                    <input type="password" name="current_password" class="form-control @error('current_password') border-danger @enderror" required>
                                    @error('current_password') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.new_password') ?? 'New Password' }} *</label>
                                    <input type="password" name="password" class="form-control @error('password') border-danger @enderror" required>
                                    @error('password') <small style="color:var(--bad)">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label" style="font-size:.82rem;font-weight:600;color:var(--ink)">{{ __('messages.confirm_password') ?? 'Confirm Password' }} *</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="mt-btn mt-btn-ink">{{ __('messages.change_password') ?? 'Change Password' }}</button>
                        </form>
                    </div>

                    {{-- Recent orders --}}
                    @if($orders->count())
                        <div class="sidebar-widget">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                                <h4 class="sidebar-title" style="margin-bottom:0;padding-bottom:0">{{ __('messages.recent_orders') ?? 'Recent Orders' }}</h4>
                                <a href="{{ route('orders.index') }}" class="mt-btn mt-btn-outline mt-btn-sm">{{ __('messages.view_all') }}</a>
                            </div>
                            <div style="overflow-x:auto">
                                <table class="table mb-0" style="min-width:400px">
                                    <thead>
                                        <tr style="background:var(--stone-50)">
                                            @foreach([__('messages.order_number') ?? '#', __('messages.total'), __('messages.status'), ''] as $th)
                                                <th style="padding:.7rem 1rem;font-size:.72rem;font-weight:700;letter-spacing:.03em;text-transform:uppercase;color:var(--stone-500);border:0;border-bottom:1px solid var(--stone-100)">{{ $th }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        @php $sc = match($order->status) { 'confirmed','delivered' => 'background:#e9f9ef;color:#1f9d55', 'pending','processing' => 'background:#fff8e1;color:#e0a400', 'cancelled' => 'background:#fdecea;color:#e0473a', default => 'background:var(--stone-100);color:var(--stone-700)' }; @endphp
                                        <tr style="border-bottom:1px solid var(--stone-100)">
                                            <td style="padding:.75rem 1rem;font-weight:700;color:var(--ink)">{{ $order->order_number }}</td>
                                            <td style="padding:.75rem 1rem;font-weight:600">{{ $order->formatted_total }}</td>
                                            <td style="padding:.75rem 1rem"><span style="border-radius:999px;font-size:.68rem;font-weight:700;padding:.25rem .65rem;{{ $sc }}">{{ $order->status_label }}</span></td>
                                            <td style="padding:.75rem 1rem"><a href="{{ route('orders.show', $order) }}" class="mt-btn mt-btn-outline mt-btn-sm">{{ __('messages.view') ?? 'View' }}</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
