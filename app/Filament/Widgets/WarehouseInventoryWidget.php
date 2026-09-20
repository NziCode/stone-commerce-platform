<?php

namespace App\Filament\Widgets;

use App\Filament\Support\StoneSaleActions;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\StoneReport;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

/** Dashboard: what every warehouse holds right now. */
class WarehouseInventoryWidget extends BaseWidget
{
    protected static ?string $heading = 'موجودی لحظه‌ای انبارها';
    protected static ?int $sort = -20;
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return StoneSaleActions::allowed();
    }

    public function table(Table $table): Table
    {
        $tons = (new StoneReport())->rows('warehouse')->keyBy('id');

        return $table
            ->query(Warehouse::query()->where('is_active', true)->withCount([
                'products as available_count' => fn ($q) => $q->where('status', 'available'),
                'products as reserved_count'  => fn ($q) => $q->where('status', 'reserved'),
                'products as sold_count'      => fn ($q) => $q->where('status', 'sold'),
            ]))
            ->description(function () {
                $none = Product::whereNull('warehouse_id')->where('status', '!=', 'sold')->count();

                return $none ? "سنگ‌های بدون انبار: {$none} — در «محصولات» چند سنگ را انتخاب و «تنظیم گروهی» را بزنید (یا در ویرایش هر سنگ، تب «دسته اصلی و انبار»)." : null;
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('انبار')->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('location')->label('محل')->placeholder('—'),
                Tables\Columns\TextColumn::make('available_count')->label('موجود')->badge()->color('success'),
                Tables\Columns\TextColumn::make('reserved_count')->label('رزرو')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('sold_count')->label('فروخته‌شده')->badge()->color('danger'),
                Tables\Columns\TextColumn::make('available_tons')
                    ->label('تن موجود')
                    ->state(fn (Warehouse $record) => number_format($tons[$record->id]['available_tons'] ?? 0, 1)),
            ])
            ->paginated(false)
            ->emptyStateHeading('هنوز انباری تعریف نشده')
            ->emptyStateDescription('از منوی «انبار و فروش ← انبارها» انبار بسازید و بعد به هر سنگ یک انبار بدهید.');
    }
}
