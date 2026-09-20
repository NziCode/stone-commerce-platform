<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseResource\Pages;
use App\Filament\Support\InventoryLookupResource;
use App\Models\Warehouse;
use Filament\Forms;
use Filament\Tables;

/** انبار: back-office list, administrators only. Never shown to visitors. */
class WarehouseResource extends InventoryLookupResource
{
    protected static ?string $model = Warehouse::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $modelLabel = 'انبار';
    protected static ?string $pluralModelLabel = 'انبارها';
    protected static ?string $navigationLabel = 'انبارها';
    protected static ?int $navigationSort = 4;

    protected static function extraFields(): array
    {
        return [
            Forms\Components\TextInput::make('location')->label('نشانی / محل')->maxLength(191),
        ];
    }

    protected static function extraColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('location')->label('محل')->placeholder('—'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWarehouses::route('/'),
        ];
    }
}
