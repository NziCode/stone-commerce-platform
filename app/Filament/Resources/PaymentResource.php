<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.payments');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.orders');
    }

    public static function getModelLabel(): string
    {
        return __('admin.payments');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.payments');
    }

    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'فروش';
    protected static ?string $modelLabel = 'پرداخت';
    protected static ?string $pluralModelLabel = 'پرداخت‌ها';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('اطلاعات پرداخت')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('order.order_number')
                        ->label('شماره سفارش')
                        ->disabled(),

                    Forms\Components\TextInput::make('type')
                        ->label('نوع')
                        ->disabled(),

                    Forms\Components\Select::make('status')
                        ->label('وضعیت')
                        ->options([
                            'pending'   => 'در انتظار',
                            'paid'      => 'پرداخت شده',
                            'failed'    => 'ناموفق',
                            'cancelled' => 'لغو شده',
                            'refunded'  => 'مسترد شده',
                        ]),

                    Forms\Components\TextInput::make('amount')
                        ->label('مبلغ')
                        ->disabled(),

                    Forms\Components\TextInput::make('currency')
                        ->label('ارز')
                        ->disabled(),

                    Forms\Components\TextInput::make('gateway')
                        ->label('درگاه')
                        ->disabled(),

                    Forms\Components\TextInput::make('transaction_id')
                        ->label('شناسه تراکنش')
                        ->disabled(),
                ]),
            ]),

            Forms\Components\Section::make('فیش بانکی')
                ->visible(fn($record) => $record?->type === 'receipt')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('bank_name')->label('نام بانک')->disabled(),
                        Forms\Components\TextInput::make('bank_country')->label('کشور بانک')->disabled(),
                        Forms\Components\TextInput::make('transfer_reference')->label('شماره حواله')->disabled(),
                        Forms\Components\DatePicker::make('receipt_date')->label('تاریخ فیش')->disabled(),
                    ]),
                    Forms\Components\Textarea::make('receipt_notes')->label('توضیحات')->disabled(),
                    Forms\Components\SpatieMediaLibraryFileUpload::make('receipt')
                        ->label('فایل فیش')
                        ->collection('receipt')
                        ->disabled(),
                ]),

            Forms\Components\Section::make('تأیید ادمین')->schema([
                Forms\Components\Textarea::make('admin_notes')
                    ->label('یادداشت ادمین')
                    ->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('شماره سفارش')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->color(fn($state) => $state === 'online' ? 'success' : 'warning')
                    ->formatStateUsing(fn($state) => $state === 'online' ? 'آنلاین' : 'فیش'),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'paid'      => 'success',
                        'pending'   => 'warning',
                        'failed'    => 'danger',
                        'cancelled' => 'gray',
                        'refunded'  => 'orange',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'paid'      => 'پرداخت شده',
                        'pending'   => 'در انتظار',
                        'failed'    => 'ناموفق',
                        'cancelled' => 'لغو',
                        'refunded'  => 'مسترد',
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('مبلغ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency')->label('ارز'),

                Tables\Columns\TextColumn::make('gateway')->label('درگاه')->badge(),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('تأیید شده در')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع')
                    ->options(['online' => 'آنلاین', 'receipt' => 'فیش']),

                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار',
                        'paid'    => 'پرداخت شده',
                        'failed'  => 'ناموفق',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('تأیید پرداخت')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->verify(auth()->id()))
                    ->visible(fn($record) => $record->type === 'receipt' && $record->status === 'pending'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'edit'  => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
