<?php

namespace App\Filament\Widgets;

use App\Filament\Support\StoneSaleActions;
use App\Services\StoneReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/** Dashboard: how many stones are available / reserved / sold / not sold, with tonnage and revenue. */
class StoneSalesOverview extends BaseWidget
{
    protected static ?int $sort = -30;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return StoneSaleActions::allowed();
    }

    protected function getStats(): array
    {
        $t = (new StoneReport())->totals();
        $tons = fn ($v) => number_format($v, 1) . ' تن';
        $unsold = $t['total'] - $t['sold'];

        return [
            Stat::make('موجود برای فروش', $t['available'])
                ->description($tons($t['available_tons']))
                ->descriptionIcon('heroicon-o-cube')
                ->color('success'),

            Stat::make('رزرو شده', $t['reserved'])
                ->description($tons($t['reserved_tons']))
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('فروخته‌شده', $t['sold'])
                ->description($tons($t['sold_tons']) . ' — ' . StoneReport::revenueLabel($t['revenue']))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('danger'),

            Stat::make('فروخته‌نشده', $unsold)
                ->description('از مجموع ' . $t['total'] . ' سنگ')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('gray'),
        ];
    }
}
