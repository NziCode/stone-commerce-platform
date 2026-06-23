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
    protected static bool $isLazy = true;

    public static function getHeading(): string
    {
        return __('admin.latest_orders');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label(__('admin.order_number'))
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label(__('admin.customer'))
                    ->description(fn($record) => $record->customer_email),

                Tables\Columns\TextColumn::make('customer_country')
                    ->label(__('admin.country'))
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('admin.status'))
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'confirmed'  => 'success',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn($state) => __('admin.order_status_' . $state)),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label(__('admin.payment_type'))
                    ->badge()
                    ->color(fn($state) => $state === 'online' ? 'success' : 'warning')
                    ->formatStateUsing(fn($state) => $state === 'online'
                        ? __('admin.payment_online')
                        : __('admin.payment_receipt')),

                Tables\Columns\TextColumn::make('total')
                    ->label(__('admin.total'))
                    ->formatStateUsing(fn($state, $record) =>
                        number_format($state) . ' ' . $record->currency)
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.date'))
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('admin.view'))
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn($record) => route('filament.admin.resources.orders.edit', $record)),
            ])
            ->paginated(false);
    }
}
