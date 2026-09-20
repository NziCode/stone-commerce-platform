<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MineResource\Pages;
use App\Filament\Support\InventoryLookupResource;
use App\Models\Mine;
use Filament\Forms;
use Filament\Tables;

/** معدن: back-office list, administrators only. Never shown to visitors. */
class MineResource extends InventoryLookupResource
{
    protected static ?string $model = Mine::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $modelLabel = 'معدن';
    protected static ?string $pluralModelLabel = 'معادن';
    protected static ?string $navigationLabel = 'معادن';
    protected static ?int $navigationSort = 3;

    protected static function extraFields(): array
    {
        return [
            Forms\Components\TextInput::make('location')->label('محل (شهر/منطقه)')->maxLength(191),
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
            'index' => Pages\ManageMines::route('/'),
        ];
    }
}
