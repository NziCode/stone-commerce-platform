<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('شماره سفارش')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('مشتری'),

                Tables\Columns\TextColumn::make('customer_country')
                    ->label('کشور')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'confirmed'  => 'success',
                        'shipped'    => 'indigo',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'pending'    => 'در انتظار',
                        'processing' => 'بررسی',
                        'confirmed'  => 'تأیید',
                        'shipped'    => 'ارسال',
                        'delivered'  => 'تحویل',
                        'cancelled'  => 'لغو',
                        default      => $state,
                    }),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label('پرداخت')
                    ->badge()
                    ->color(fn($state) => $state === 'online' ? 'success' : 'warning')
                    ->formatStateUsing(fn($state) => $state === 'online' ? 'آنلاین' : 'فیش'),

                Tables\Columns\TextColumn::make('total')
                    ->label('جمع کل')
                    ->formatStateUsing(fn($state, $record) => number_format($state) . ' ' . $record->currency),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('مشاهده')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.admin.resources.orders.edit', $record)),
            ]);
    }
}
