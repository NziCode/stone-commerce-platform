<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $today        = now()->startOfDay();
        $thisMonth    = now()->startOfMonth();
        $lastMonth    = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        // Revenue comparison
        $revenueThisMonth = Order::where('status', 'confirmed')
            ->where('created_at', '>=', $thisMonth)
            ->sum('total');

        $revenueLastMonth = Order::where('status', 'confirmed')
            ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
            ->sum('total');

        $revenueChange = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
            : 0;

        // Orders this month vs last
        $ordersThisMonth = Order::where('created_at', '>=', $thisMonth)->count();
        $ordersLastMonth = Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();

        // 7-day chart data
        $orderChart = collect(range(6, 0))->map(fn($i) =>
            Order::whereDate('created_at', now()->subDays($i))->count()
        )->toArray();

        $revenueChart = collect(range(6, 0))->map(fn($i) =>
            (int) Order::where('status', 'confirmed')
                ->whereDate('created_at', now()->subDays($i))
                ->sum('total')
        )->toArray();

        $productChart = collect(range(6, 0))->map(fn($i) =>
            Product::whereDate('created_at', now()->subDays($i))->count()
        )->toArray();

        return [
            Stat::make(__('admin.stat_revenue_month'), number_format($revenueThisMonth) . ' ﷼')
                ->description($revenueChange >= 0
                    ? '+' . $revenueChange . '% ' . __('admin.vs_last_month')
                    : $revenueChange . '% ' . __('admin.vs_last_month'))
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueChange >= 0 ? 'success' : 'danger')
                ->chart($revenueChart),

            Stat::make(__('admin.stat_orders_month'), $ordersThisMonth)
                ->description(__('admin.stat_pending') . ': ' . Order::where('status', 'pending')->count())
                ->descriptionIcon('heroicon-o-clock')
                ->color('info')
                ->chart($orderChart),

            Stat::make(__('admin.stat_available_products'), Product::where('status', 'available')->count())
                ->description(__('admin.stat_total') . ': ' . Product::count() . ' | ' . __('admin.stat_sold') . ': ' . Product::where('status', 'sold')->count())
                ->descriptionIcon('heroicon-o-cube')
                ->color('success')
                ->chart($productChart),

            Stat::make(__('admin.stat_new_users'), User::where('created_at', '>=', $thisMonth)->count())
                ->description(__('admin.stat_total_users') . ': ' . User::count())
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make(__('admin.stat_pending_reviews'), Review::where('status', 'pending')->count())
                ->description(__('admin.stat_total_reviews') . ': ' . Review::where('status', 'approved')->count())
                ->descriptionIcon('heroicon-o-star')
                ->color(Review::where('status', 'pending')->count() > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.reviews.index') . '?tableFilters[status][value]=pending'),

            Stat::make(__('admin.stat_new_messages'), ContactMessage::where('status', 'new')->count())
                ->description(__('admin.stat_newsletter') . ': ' . Newsletter::active()->count())
                ->descriptionIcon('heroicon-o-envelope')
                ->color(ContactMessage::where('status', 'new')->count() > 0 ? 'danger' : 'gray')
                ->url(route('filament.admin.resources.contact-messages.index')),
        ];
    }
}
