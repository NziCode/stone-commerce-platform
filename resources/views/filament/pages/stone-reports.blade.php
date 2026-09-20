<x-filament-panels::page>
    @php
        $th = 'padding:.65rem .8rem;text-align:start;font-size:.78rem;font-weight:700;color:#6b7280;border-bottom:1px solid #e5e7eb;white-space:nowrap';
        $td = 'padding:.65rem .8rem;font-size:.86rem;border-bottom:1px solid #f3f4f6;white-space:nowrap';
        $card = 'background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem 1.2rem';
        $num = fn ($v) => number_format((float) $v, 1);
    @endphp

    <div style="{{ $card }}">
        {{ $this->form }}
        <p style="margin:.7rem 0 0;font-size:.74rem;color:#9ca3af">
            بازهٔ تاریخ فقط «فروش‌ها» را محدود می‌کند؛ موجودی و رزروها همیشه وضعیت لحظه‌ای هستند. فروش هر سنگ به انبارِ همان زمان فروش حساب می‌شود.
        </p>
    </div>

    {{-- totals --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:.8rem">
        @foreach([
            ['کل سنگ‌ها', $totals['total'], null, '#111827'],
            ['موجود', $totals['available'], $num($totals['available_tons']) . ' تن', '#15803d'],
            ['رزرو', $totals['reserved'], $num($totals['reserved_tons']) . ' تن', '#b45309'],
            ['فروخته‌شده', $totals['sold'], $num($totals['sold_tons']) . ' تن', '#b91c1c'],
            ['درآمد فروش', \App\Services\StoneReport::revenueLabel($totals['revenue']), null, '#1d4ed8'],
        ] as [$label, $value, $sub, $color])
            <div style="{{ $card }}">
                <div style="font-size:.74rem;color:#6b7280">{{ $label }}</div>
                <div style="font-size:1.35rem;font-weight:800;color:{{ $color }};margin-top:.15rem">{{ $value }}</div>
                @if($sub)<div style="font-size:.74rem;color:#9ca3af">{{ $sub }}</div>@endif
            </div>
        @endforeach
    </div>

    {{-- dimension tabs + table --}}
    <div style="{{ $card }};padding:0;overflow:hidden">
        <div style="display:flex;flex-wrap:wrap;gap:.4rem;padding:.8rem 1rem;border-bottom:1px solid #e5e7eb">
            @foreach($dimensions as $key => $label)
                <button type="button" wire:click="setDimension('{{ $key }}')"
                        style="padding:.45rem .95rem;border-radius:8px;border:0;cursor:pointer;font-family:inherit;font-size:.82rem;font-weight:700;{{ $dimension === $key ? 'background:linear-gradient(135deg,#ff5a1f,#ff8a3d);color:#fff' : 'background:#f3f4f6;color:#374151' }}">
                    بر اساس {{ $label }}
                </button>
            @endforeach
        </div>

        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr>
                        <th style="{{ $th }}">{{ $dimensions[$dimension] }}</th>
                        <th style="{{ $th }}">کل</th>
                        <th style="{{ $th }}">موجود</th>
                        <th style="{{ $th }}">رزرو</th>
                        <th style="{{ $th }}">فروخته‌شده</th>
                        <th style="{{ $th }}">تن موجود</th>
                        <th style="{{ $th }}">تن فروخته‌شده</th>
                        <th style="{{ $th }}">درآمد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td style="{{ $td }};font-weight:700">{{ $row['name'] }}</td>
                            <td style="{{ $td }}">{{ $row['total'] }}</td>
                            <td style="{{ $td }};color:#15803d">{{ $row['available'] }}</td>
                            <td style="{{ $td }};color:#b45309">{{ $row['reserved'] }}</td>
                            <td style="{{ $td }};color:#b91c1c">{{ $row['sold'] }}</td>
                            <td style="{{ $td }}">{{ $num($row['available_tons']) }}</td>
                            <td style="{{ $td }}">{{ $num($row['sold_tons']) }}</td>
                            <td style="{{ $td }}" dir="ltr">{{ \App\Services\StoneReport::revenueLabel($row['revenue']) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="{{ $td }};text-align:center;color:#9ca3af;padding:1.6rem">سنگی با این فیلترها پیدا نشد.</td></tr>
                    @endforelse
                </tbody>
                @if($rows->count())
                    <tfoot>
                        <tr style="background:#f9fafb">
                            <td style="{{ $td }};font-weight:800">جمع</td>
                            <td style="{{ $td }};font-weight:800">{{ $totals['total'] }}</td>
                            <td style="{{ $td }};font-weight:800">{{ $totals['available'] }}</td>
                            <td style="{{ $td }};font-weight:800">{{ $totals['reserved'] }}</td>
                            <td style="{{ $td }};font-weight:800">{{ $totals['sold'] }}</td>
                            <td style="{{ $td }};font-weight:800">{{ $num($totals['available_tons']) }}</td>
                            <td style="{{ $td }};font-weight:800">{{ $num($totals['sold_tons']) }}</td>
                            <td style="{{ $td }};font-weight:800" dir="ltr">{{ \App\Services\StoneReport::revenueLabel($totals['revenue']) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-filament-panels::page>
