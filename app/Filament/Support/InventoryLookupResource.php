<?php

namespace App\Filament\Support;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Shared shape of the three back-office lists a stone can be assigned to — owners, mines and
 * warehouses: a name, a few extra fields, notes, an active switch and how many stones each holds.
 * Administrators only (see RestrictedToAdmins).
 */
abstract class InventoryLookupResource extends Resource
{
    use RestrictedToAdmins;

    protected static ?string $navigationGroup = 'انبار و فروش';

    /** @return array<int, Forms\Components\Component> */
    abstract protected static function extraFields(): array;

    /** @return array<int, Tables\Columns\Column> */
    abstract protected static function extraColumns(): array;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount([
            'products',
            'products as available_count' => fn ($q) => $q->where('status', 'available'),
            'products as reserved_count'  => fn ($q) => $q->where('status', 'reserved'),
            'products as sold_count'      => fn ($q) => $q->where('status', 'sold'),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('نام')->required()->maxLength(150),
            ...static::extraFields(),
            Forms\Components\Textarea::make('notes')->label('یادداشت')->rows(3)->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->label('فعال')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('نام')->searchable()->sortable(),
                ...static::extraColumns(),
                Tables\Columns\TextColumn::make('products_count')->label('سنگ‌ها')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('available_count')->label('موجود')->badge()->color('success'),
                Tables\Columns\TextColumn::make('reserved_count')->label('رزرو')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('sold_count')->label('فروخته‌شده')->badge()->color('danger'),
                Tables\Columns\ToggleColumn::make('is_active')->label('فعال'),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\TrashedFilter::make()->label('حذف‌شده‌ها'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('ویرایش'),
                Tables\Actions\DeleteAction::make()->label('حذف')->modalDescription('سنگ‌هایی که به این مورد وصل‌اند سر جایشان می‌مانند و گزارش‌های قبلی تغییر نمی‌کنند.'),
                Tables\Actions\RestoreAction::make()->label('بازگردانی'),
            ]);
    }
}
