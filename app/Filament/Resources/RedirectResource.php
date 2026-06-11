<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RedirectResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.redirects');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.settings');
    }

    public static function getModelLabel(): string
    {
        return __('admin.redirects');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.redirects');
    }

    protected static ?string $model = Redirect::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-right';
    protected static ?string $navigationGroup = 'تنظیمات';
    protected static ?string $modelLabel = 'ریدایرکت';
    protected static ?string $pluralModelLabel = 'ریدایرکت‌ها';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('from_url')
                    ->label('از URL')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('مثال: /old-page'),

                Forms\Components\TextInput::make('to_url')
                    ->label('به URL')
                    ->required()
                    ->helperText('مثال: /new-page'),

                Forms\Components\Select::make('status_code')
                    ->label('نوع ریدایرکت')
                    ->options([
                        301 => '301 — دائمی',
                        302 => '302 — موقت',
                    ])
                    ->default(301)
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('فعال')
                    ->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_url')
                    ->label('از')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('to_url')
                    ->label('به')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status_code')
                    ->label('نوع')
                    ->badge()
                    ->color(fn($state) => $state === 301 ? 'warning' : 'info'),

                Tables\Columns\TextColumn::make('hits')
                    ->label('تعداد بازدید')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),
            ])
            ->defaultSort('hits', 'desc')
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
            'index'  => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit'   => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
}
