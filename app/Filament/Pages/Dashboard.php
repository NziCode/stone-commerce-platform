<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort    = -2;

    public static function getNavigationLabel(): string
    {
        return __('admin.dashboard');
    }

    public function getTitle(): string
    {
        return __('admin.dashboard');
    }

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\LatestOrders::class,
            \App\Filament\Widgets\ProductStatusChart::class,
            \App\Filament\Widgets\RevenueChart::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
