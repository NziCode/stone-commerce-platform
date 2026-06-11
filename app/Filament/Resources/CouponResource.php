<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.coupons');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.store');
    }

    public static function getModelLabel(): string
    {
        return __('admin.coupons');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.coupons');
    }

    protected static ?string $model = Coupon::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'فروش';
    protected static ?string $modelLabel = 'کوپن';
    protected static ?string $pluralModelLabel = 'کوپن‌ها';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('کد کوپن')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50)
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('generate')
                                ->icon('heroicon-o-arrow-path')
                                ->action(fn($component) => $component->state(strtoupper(\Illuminate\Support\Str::random(8))))
                        ),

                    Forms\Components\Select::make('type')
                        ->label('نوع تخفیف')
                        ->options(['percent' => 'درصدی', 'fixed' => 'مبلغ ثابت'])
                        ->required()
                        ->default('percent')
                        ->live(),

                    Forms\Components\TextInput::make('value')
                        ->label(fn($get) => $get('type') === 'percent' ? 'درصد تخفیف' : 'مبلغ تخفیف')
                        ->numeric()
                        ->required()
                        ->suffix(fn($get) => $get('type') === 'percent' ? '%' : '﷼'),

                    Forms\Components\TextInput::make('min_order_amount')
                        ->label('حداقل مبلغ سفارش')
                        ->numeric(),

                    Forms\Components\TextInput::make('max_discount_amount')
                        ->label('حداکثر مبلغ تخفیف')
                        ->numeric(),

                    Forms\Components\TextInput::make('usage_limit')
                        ->label('حداکثر استفاده کل')
                        ->numeric(),

                    Forms\Components\TextInput::make('usage_per_user')
                        ->label('استفاده هر کاربر')
                        ->numeric()
                        ->default(1),

                    Forms\Components\DateTimePicker::make('starts_at')
                        ->label('تاریخ شروع'),

                    Forms\Components\DateTimePicker::make('expires_at')
                        ->label('تاریخ انقضا'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('فعال')
                        ->default(true),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('کد')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'percent' ? 'درصدی' : 'ثابت'),

                Tables\Columns\TextColumn::make('value')
                    ->label('مقدار')
                    ->formatStateUsing(fn($state, $record) =>
                    $record->type === 'percent' ? "{$state}%" : number_format($state) . '﷼'
                    ),

                Tables\Columns\TextColumn::make('used_count')
                    ->label('استفاده شده')
                    ->sortable(),

                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('حداکثر')
                    ->default('نامحدود'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('انقضا')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('فعال'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
