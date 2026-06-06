<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LanguageResource\Pages;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationGroup = 'تنظیمات';
    protected static ?string $modelLabel = 'زبان';
    protected static ?string $pluralModelLabel = 'زبان‌ها';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('نام (انگلیسی)')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\TextInput::make('native_name')
                        ->label('نام بومی')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\TextInput::make('code')
                        ->label('کد زبان')
                        ->required()
                        ->maxLength(10)
                        ->unique(ignoreRecord: true)
                        ->helperText('مثال: fa, en, ar'),

                    Forms\Components\TextInput::make('locale')
                        ->label('Locale')
                        ->required()
                        ->maxLength(10)
                        ->unique(ignoreRecord: true)
                        ->helperText('مثال: fa_IR, en_US'),

                    Forms\Components\Select::make('direction')
                        ->label('جهت نوشتار')
                        ->options(['ltr' => 'چپ به راست', 'rtl' => 'راست به چپ'])
                        ->required()
                        ->default('ltr'),

                    Forms\Components\TextInput::make('flag')
                        ->label('پرچم')
                        ->maxLength(10)
                        ->helperText('مثال: 🇮🇷'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('ترتیب نمایش')
                        ->numeric()
                        ->default(0),
                ]),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Toggle::make('is_default')
                        ->label('زبان پیش‌فرض')
                        ->default(false),

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
                Tables\Columns\TextColumn::make('flag')
                    ->label('')
                    ->width(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('native_name')
                    ->label('نام بومی')
                    ->searchable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('کد')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('direction')
                    ->label('جهت')
                    ->badge()
                    ->color(fn($state) => $state === 'rtl' ? 'warning' : 'success'),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('پیش‌فرض')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ترتیب')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
            'index'  => Pages\ListLanguages::route('/'),
            'create' => Pages\CreateLanguage::route('/create'),
            'edit'   => Pages\EditLanguage::route('/{record}/edit'),
        ];
    }
}
