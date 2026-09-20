<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnerResource\Pages;
use App\Filament\Support\InventoryLookupResource;
use App\Models\Owner;
use Filament\Forms;
use Filament\Tables;

/** مالک: back-office list, administrators only. Never shown to visitors. */
class OwnerResource extends InventoryLookupResource
{
    protected static ?string $model = Owner::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $modelLabel = 'مالک';
    protected static ?string $pluralModelLabel = 'مالکان';
    protected static ?string $navigationLabel = 'مالکان';
    protected static ?int $navigationSort = 2;

    protected static function extraFields(): array
    {
        return [
            Forms\Components\TextInput::make('phone')->label('تلفن')->tel()->maxLength(40),
            Forms\Components\TextInput::make('email')->label('ایمیل')->email()->maxLength(150),
        ];
    }

    protected static function extraColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('phone')->label('تلفن')->placeholder('—')->copyable(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOwners::route('/'),
        ];
    }
}
