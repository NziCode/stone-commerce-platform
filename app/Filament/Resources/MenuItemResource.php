<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'ظاهر سایت';
    protected static ?string $modelLabel = 'آیتم منو';
    protected static ?string $pluralModelLabel = 'آیتم‌های منو';
    protected static ?int $navigationSort = 3;
    protected static bool $shouldRegisterNavigation = false; // در منوی ناوبری نشون نمیده

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية'];

        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('menu_id')
                        ->label('منو')
                        ->relationship('menu', 'name')
                        ->required()
                        ->live(),

                    Forms\Components\Select::make('parent_id')
                        ->label('آیتم والد')
                        ->options(fn($get) =>
                        MenuItem::where('menu_id', $get('menu_id'))
                            ->whereNull('parent_id')
                            ->get()
                            ->mapWithKeys(fn($item) => [
                                $item->id => $item->getTranslation('label', 'fa')
                            ])
                        )
                        ->nullable()
                        ->searchable(),

                    Forms\Components\TextInput::make('url')
                        ->label('لینک (URL)')
                        ->url(),

                    Forms\Components\TextInput::make('route_name')
                        ->label('نام Route')
                        ->maxLength(100),

                    Forms\Components\Select::make('target')
                        ->label('هدف لینک')
                        ->options(['_self' => 'همین تب', '_blank' => 'تب جدید'])
                        ->default('_self'),

                    Forms\Components\TextInput::make('icon')
                        ->label('آیکون')
                        ->maxLength(100)
                        ->helperText('مثال: heroicon-o-home'),

                    Forms\Components\TextInput::make('css_class')
                        ->label('کلاس CSS')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('ترتیب نمایش')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->label('فعال')
                        ->default(true),
                ]),

                Forms\Components\Tabs::make('translations')->tabs(
                    collect($locales)->map(fn($label, $code) =>
                    Forms\Components\Tabs\Tab::make($label)->schema([
                        Forms\Components\TextInput::make("label.{$code}")
                            ->label('برچسب منو')
                            ->required($code === 'fa'),
                    ])
                    )->toArray()
                )->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('menu.name')
                    ->label('منو')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('label')
                    ->label('برچسب')
                    ->getStateUsing(fn($record) => $record->getTranslation('label', 'fa'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('parent.label')
                    ->label('والد')
                    ->getStateUsing(fn($record) => $record->parent?->getTranslation('label', 'fa') ?? '—'),

                Tables\Columns\TextColumn::make('url')
                    ->label('لینک')
                    ->limit(40)
                    ->copyable(),

                Tables\Columns\TextColumn::make('target')
                    ->label('هدف')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === '_blank' ? 'تب جدید' : 'همین تب'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ترتیب')
                    ->sortable(),
            ])
            ->defaultSort('menu_id')
            ->groups([
                Tables\Grouping\Group::make('menu.name')->label('منو'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('menu_id')
                    ->label('منو')
                    ->relationship('menu', 'name'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('فعال'),
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
            'index'  => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit'   => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
