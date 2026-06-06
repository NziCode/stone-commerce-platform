<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductStatusChart extends ChartWidget
{
    protected static ?string $heading = 'وضعیت محصولات';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $available   = Product::where('status', 'available')->count();
        $unavailable = Product::where('status', 'unavailable')->count();
        $reserved    = Product::where('status', 'reserved')->count();
        $sold        = Product::where('status', 'sold')->count();

        return [
            'datasets' => [
                [
                    'label'           => 'محصولات',
                    'data'            => [$available, $unavailable, $reserved, $sold],
                    'backgroundColor' => ['#22c55e', '#94a3b8', '#f59e0b', '#ef4444'],
                ],
            ],
            'labels' => ['موجود', 'ناموجود', 'رزرو', 'فروخته شده'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
