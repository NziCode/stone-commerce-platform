<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'فروش';
    protected static ?string $modelLabel = 'سفارش';
    protected static ?string $pluralModelLabel = 'سفارشات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('اطلاعات سفارش')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('order_number')
                        ->label('شماره سفارش')
                        ->disabled(),

                    Forms\Components\Select::make('status')
                        ->label('وضعیت')
                        ->options([
                            'pending'    => 'در انتظار پرداخت',
                            'processing' => 'در حال بررسی',
                            'confirmed'  => 'تأیید شده',
                            'shipped'    => 'ارسال شده',
                            'delivered'  => 'تحویل داده شده',
                            'cancelled'  => 'لغو شده',
                            'refunded'   => 'مسترد شده',
                        ])
                        ->required(),

                    Forms\Components\Select::make('payment_type')
                        ->label('نوع پرداخت')
                        ->options(['online' => 'آنلاین', 'receipt' => 'فیش بانکی'])
                        ->disabled(),
                ]),
            ]),

            Forms\Components\Section::make('اطلاعات مشتری')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('customer_name')->label('نام')->disabled(),
                    Forms\Components\TextInput::make('customer_email')->label('ایمیل')->disabled(),
                    Forms\Components\TextInput::make('customer_phone')->label('تلفن')->disabled(),
                    Forms\Components\TextInput::make('customer_company')->label('شرکت')->disabled(),
                    Forms\Components\TextInput::make('customer_country')->label('کشور')->disabled(),
                    Forms\Components\Textarea::make('customer_address')->label('آدرس')->disabled()->columnSpanFull(),
                ]),
            ]),

            Forms\Components\Section::make('مالی')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('subtotal')->label('جمع جزء')->disabled(),
                    Forms\Components\TextInput::make('discount_amount')->label('تخفیف')->disabled(),
                    Forms\Components\TextInput::make('total')->label('جمع کل')->disabled(),
                    Forms\Components\TextInput::make('currency')->label('ارز')->disabled(),
                ]),
            ]),

            Forms\Components\Section::make('یادداشت ادمین')->schema([
                Forms\Components\Textarea::make('admin_notes')
                    ->label('یادداشت')
                    ->rows(3),

                Forms\Components\TextInput::make('tracking_code')
                    ->label('کد رهگیری مرسوله'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('شماره سفارش')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('مشتری')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_email')
                    ->label('ایمیل')
                    ->searchable(),

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
                        'refunded'   => 'orange',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'pending'    => 'در انتظار',
                        'processing' => 'بررسی',
                        'confirmed'  => 'تأیید',
                        'shipped'    => 'ارسال',
                        'delivered'  => 'تحویل',
                        'cancelled'  => 'لغو',
                        'refunded'   => 'مسترد',
                        default      => $state,
                    }),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label('پرداخت')
                    ->badge()
                    ->color(fn($state) => $state === 'online' ? 'success' : 'warning')
                    ->formatStateUsing(fn($state) => $state === 'online' ? 'آنلاین' : 'فیش'),

                Tables\Columns\TextColumn::make('total')
                    ->label('جمع کل')
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('ارز'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending'    => 'در انتظار',
                        'processing' => 'بررسی',
                        'confirmed'  => 'تأیید',
                        'shipped'    => 'ارسال',
                        'delivered'  => 'تحویل',
                        'cancelled'  => 'لغو',
                    ]),

                Tables\Filters\SelectFilter::make('payment_type')
                    ->label('نوع پرداخت')
                    ->options(['online' => 'آنلاین', 'receipt' => 'فیش']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('confirm')
                    ->label('تأیید سفارش')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->confirm())
                    ->visible(fn($record) => $record->status === 'processing'),

                Tables\Actions\Action::make('cancel')
                    ->label('لغو')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->cancel())
                    ->visible(fn($record) => in_array($record->status, ['pending', 'processing'])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
