<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'درآمد ۳۰ روز اخیر';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $days    = collect(range(29, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));
        $orders  = Order::where('status', 'confirmed')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [
                [
                    'label'           => 'درآمد (ریال)',
                    'data'            => $days->map(fn($d) => $orders[$d] ?? 0)->toArray(),
                    'borderColor'     => '#f59e0b',
                    'backgroundColor' => 'rgba(245,158,11,0.1)',
                    'fill'            => true,
                ],
            ],
            'labels' => $days->map(fn($d) => Carbon::parse($d)->format('m/d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
