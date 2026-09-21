<?php

namespace App\Filament\Widgets;

use App\Filament\Support\StoneSaleActions;
use App\Models\MainCategory;
use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

/** Dashboard: every stone with its status — which ones sold and which did not — and a button to record / cancel a sale. */
class StoneSalesTableWidget extends BaseWidget
{
    protected static ?string $heading = 'وضعیت فروش سنگ‌ها';
    protected static ?int $sort = -10;
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return StoneSaleActions::allowed();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->with(['mainCategory', 'warehouse', 'owner', 'mine'])->latest('created_at'))
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('main_image')->collection('main_image')->conversion('thumb')->label('')->width(56)->height(56),
                Tables\Columns\TextColumn::make('name')
                    ->label('سنگ')
                    ->state(fn (Product $r) => $r->getTranslation('name', 'fa', false) ?: $r->getTranslation('name', 'en', false) ?: '—')
                    ->description(fn (Product $r) => $r->sku)
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('sku')->label('کد')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mainCategory.name')
                    ->label('دسته اصلی')
                    ->badge()->color('info')
                    ->state(fn (Product $r) => $r->mainCategory?->getTranslation('name', 'fa', false))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('warehouse.name')->label('انبار')->placeholder('—'),
                Tables\Columns\TextColumn::make('owner.name')->label('مالک')->placeholder('—'),
                Tables\Columns\TextColumn::make('mine.name')->label('معدن')->placeholder('—'),
                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state) => ['available' => 'success', 'reserved' => 'warning', 'sold' => 'danger'][$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state) => ['available' => 'موجود', 'reserved' => 'رزرو', 'sold' => 'فروخته‌شده', 'unavailable' => 'ناموجود'][$state] ?? $state),
                Tables\Columns\TextColumn::make('sold_at')->label('تاریخ فروش')->dateTime('Y-m-d')->placeholder('—'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('sold')
                    ->label('فروش')
                    ->placeholder('همه')
                    ->trueLabel('فروخته‌شده')
                    ->falseLabel('فروخته‌نشده')
                    ->queries(
                        true: fn (Builder $q) => $q->where('status', 'sold'),
                        false: fn (Builder $q) => $q->where('status', '!=', 'sold'),
                        blank: fn (Builder $q) => $q,
                    ),
                Tables\Filters\SelectFilter::make('main_category_id')
                    ->label('دسته اصلی')
                    ->options(fn () => MainCategory::ordered()->get()->mapWithKeys(fn ($m) => [$m->id => $m->getTranslation('name', 'fa', false)])),
            ])
            ->actions([
                StoneSaleActions::recordSale(),
                StoneSaleActions::editSale(),
                StoneSaleActions::cancelSale(),
            ])
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10, 25, 50])
            ->emptyStateHeading('هنوز سنگی ثبت نشده است');
    }
}
