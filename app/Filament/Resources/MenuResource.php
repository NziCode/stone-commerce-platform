<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Support\TranslateFieldsAction;
use App\Models\Menu;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;

class MenuResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.menus');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.appearance');
    }

    public static function getModelLabel(): string
    {
        return __('admin.menus');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.menus');
    }

    protected static ?string $model = Menu::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationGroup = 'ظاهر سایت';
    protected static ?string $modelLabel = 'منو';
    protected static ?string $pluralModelLabel = 'منوها';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية', 'zh' => '中文', 'tr' => 'Türkçe'];

        return $form->schema([
            Forms\Components\Section::make('اطلاعات منو')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('نام منو')
                        ->required(),

                    Forms\Components\Select::make('location')
                        ->label('موقعیت')
                        ->options([
                            'header' => 'هدر',
                            'footer' => 'فوتر',
                            'mobile' => 'موبایل',
                        ])
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\Toggle::make('is_active')
                        ->label('فعال')
                        ->default(true),
                ]),
            ]),

            Forms\Components\Section::make('آیتم‌های منو')->schema([
                Forms\Components\Actions::make([
                    TranslateFieldsAction::make(fields: [
                        'allItems' => [
                            'label' => false,
                        ],
                    ]),
                ]),

                Repeater::make('allItems')
                    ->label('')
                    ->relationship('allItems')
                    ->schema([
                        Forms\Components\Grid::make(4)->schema([
                            Forms\Components\Tabs::make('item_translations')->tabs(
                                collect($locales)->map(fn($label, $code) =>
                                Forms\Components\Tabs\Tab::make($label)->schema([
                                    Forms\Components\TextInput::make("label.{$code}")
                                        ->label('برچسب')
                                        ->required($code === 'fa'),
                                ])
                                )->toArray()
                            )->columnSpan(2),

                            Forms\Components\TextInput::make('url')
                                ->label('لینک (URL)')
                                ->url(),

                            Forms\Components\TextInput::make('route_name')
                                ->label('نام Route'),

                            Forms\Components\Select::make('parent_id')
                                ->label('آیتم والد')
                                ->options(fn($get, $record) =>
                                MenuItem::where('menu_id', $record?->menu_id ?? 0)
                                    ->whereNull('parent_id')
                                    ->get()
                                    ->pluck('label', 'id')
                                )
                                ->nullable(),

                            Forms\Components\Select::make('target')
                                ->label('هدف')
                                ->options(['_self' => 'همین تب', '_blank' => 'تب جدید'])
                                ->default('_self'),

                            Forms\Components\TextInput::make('icon')
                                ->label('آیکون'),

                            Forms\Components\TextInput::make('sort_order')
                                ->label('ترتیب')
                                ->numeric()
                                ->default(0),

                            Forms\Components\Toggle::make('is_active')
                                ->label('فعال')
                                ->default(true),
                        ]),
                    ])
                    ->addActionLabel('افزودن آیتم')
                    ->orderColumn('sort_order')
                    ->collapsible()
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام منو')
                    ->searchable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('موقعیت')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn($state) => match($state) {
                        'header' => 'هدر',
                        'footer' => 'فوتر',
                        'mobile' => 'موبایل',
                    }),

                Tables\Columns\TextColumn::make('allItems_count')
                    ->label('تعداد آیتم‌ها')
                    ->counts('allItems')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit'   => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
