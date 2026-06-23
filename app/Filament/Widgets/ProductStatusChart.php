<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductStatusChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static bool $isLazy = true;

    public function getHeading(): string
    {
        return __('admin.product_status_chart');
    }

    protected function getData(): array
    {
        $available   = Product::where('status', 'available')->count();
        $reserved    = Product::where('status', 'reserved')->count();
        $sold        = Product::where('status', 'sold')->count();
        $unavailable = Product::where('status', 'unavailable')->count();

        return [
            'datasets' => [
                [
                    'data'            => [$available, $reserved, $sold, $unavailable],
                    'backgroundColor' => ['#22c55e', '#f59e0b', '#0b2147', '#e2e8f0'],
                    'borderWidth'     => 0,
                    'hoverOffset'     => 6,
                ],
            ],
            'labels' => [
                __('admin.status_available') . " ($available)",
                __('admin.status_reserved') . " ($reserved)",
                __('admin.status_sold') . " ($sold)",
                __('admin.status_unavailable') . " ($unavailable)",
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'bottom'],
            ],
            'cutout' => '65%',
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
