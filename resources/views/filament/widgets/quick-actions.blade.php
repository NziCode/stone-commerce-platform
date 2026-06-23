<x-filament-widgets::widget>
    <x-filament::section>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem">

            @foreach([
                [
                    'label'  => __('admin.pending_orders'),
                    'count'  => $pendingOrders,
                    'href'   => route('filament.admin.resources.orders.index') . '?tableFilters[status][value]=pending',
                    'color'  => '#e0a400',
                    'bg'     => '#fff8e1',
                    'icon'   => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                ],
                [
                    'label'  => __('admin.pending_receipts'),
                    'count'  => $pendingReceipts,
                    'href'   => route('filament.admin.resources.payments.index') . '?tableFilters[status][value]=pending',
                    'color'  => '#123a7a',
                    'bg'     => '#e8f1fd',
                    'icon'   => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                ],
                [
                    'label'  => __('admin.pending_reviews'),
                    'count'  => $pendingReviews,
                    'href'   => route('filament.admin.resources.reviews.index') . '?tableFilters[status][value]=pending',
                    'color'  => '#ff5a1f',
                    'bg'     => '#fff4f0',
                    'icon'   => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                ],
                [
                    'label'  => __('admin.new_messages'),
                    'count'  => $newMessages,
                    'href'   => route('filament.admin.resources.contact-messages.index'),
                    'color'  => '#1f9d55',
                    'bg'     => '#e9f9ef',
                    'icon'   => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                ],
            ] as $item)
                <a href="{{ $item['href'] }}"
                   style="display:flex;align-items:center;gap:1rem;padding:1.1rem 1.3rem;border-radius:12px;background:{{ $item['bg'] }};text-decoration:none;transition:.18s ease;border:1.5px solid transparent"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px -8px rgba(0,0,0,.15)'"
                   onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div style="width:46px;height:46px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,.08)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="{{ $item['color'] }}" stroke-width="2" width="22" height="22">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <strong style="display:block;font-size:1.5rem;font-weight:800;color:{{ $item['color'] }};line-height:1">
                            {{ $item['count'] }}
                        </strong>
                        <span style="font-size:.78rem;color:#6b7280;font-weight:500">{{ $item['label'] }}</span>
                    </div>
                    @if($item['count'] > 0)
                        <div style="margin-inline-start:auto;width:8px;height:8px;border-radius:50%;background:{{ $item['color'] }};animation:pulse 2s infinite"></div>
                    @endif
                </a>
            @endforeach

        </div>

        <style>
        @keyframes pulse {
            0%,100%{opacity:1} 50%{opacity:.4}
        }
        </style>

    </x-filament::section>
</x-filament-widgets::widget>
