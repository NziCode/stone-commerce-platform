<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ContactMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('محصولات موجود', Product::where('status', 'available')->count())
                ->description('از مجموع ' . Product::count() . ' محصول')
                ->descriptionIcon('heroicon-o-cube')
                ->color('success')
                ->chart(
                    Product::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupBy('date')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('سفارشات امروز', Order::whereDate('created_at', today())->count())
                ->description('در انتظار: ' . Order::where('status', 'pending')->count())
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('info')
                ->chart(
                    Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupBy('date')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('درآمد این ماه', number_format(
                    Order::whereMonth('created_at', now()->month)
                        ->where('status', 'confirmed')
                        ->sum('total')
                ) . ' ﷼')
                ->description('سفارشات تأیید شده')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('warning'),

            Stat::make('کاربران', User::count())
                ->description('جدید این هفته: ' . User::where('created_at', '>=', now()->subWeek())->count())
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('پیام‌های جدید', ContactMessage::where('status', 'new')->count())
                ->description('نیاز به پاسخ')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('danger'),

            Stat::make('محصولات فروخته شده', Product::where('status', 'sold')->count())
                ->description('این ماه: ' . Order::whereMonth('created_at', now()->month)->where('status', 'confirmed')->count())
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),
        ];
    }
}
