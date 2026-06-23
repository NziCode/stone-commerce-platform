<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';
    protected static bool $isLazy = true;

    public ?string $filter = '30';

    public function getHeading(): string
    {
        return __('admin.revenue_chart');
    }

    protected function getFilters(): ?array
    {
        return [
            '7'  => __('admin.last_7_days'),
            '30' => __('admin.last_30_days'),
            '90' => __('admin.last_90_days'),
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 30);

        $range = collect(range($days - 1, 0))->map(
            fn($i) => now()->subDays($i)->format('Y-m-d')
        );

        $confirmed = Order::where('status', 'confirmed')
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $all = Order::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as cnt')
            ->groupBy('date')
            ->pluck('cnt', 'date');

        $labels = $range->map(fn($d) =>
            $days <= 30
                ? Carbon::parse($d)->format('m/d')
                : Carbon::parse($d)->format('m/d')
        );

        return [
            'datasets' => [
                [
                    'label'           => __('admin.confirmed_revenue'),
                    'data'            => $range->map(fn($d) => (int)($confirmed[$d] ?? 0))->toArray(),
                    'borderColor'     => '#ff5a1f',
                    'backgroundColor' => 'rgba(255,90,31,0.08)',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'yAxisID'         => 'y',
                ],
                [
                    'label'           => __('admin.order_count'),
                    'data'            => $range->map(fn($d) => (int)($all[$d] ?? 0))->toArray(),
                    'borderColor'     => '#0b2147',
                    'backgroundColor' => 'rgba(11,33,71,0.05)',
                    'fill'            => false,
                    'tension'         => 0.4,
                    'yAxisID'         => 'y1',
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y'  => ['position' => 'left',  'grid' => ['display' => false]],
                'y1' => ['position' => 'right', 'grid' => ['display' => false]],
            ],
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
