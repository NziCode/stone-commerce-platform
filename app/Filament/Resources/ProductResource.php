<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'فروشگاه';
    protected static ?string $modelLabel = 'محصول';
    protected static ?string $pluralModelLabel = 'محصولات';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية'];

        return $form->schema([
            Forms\Components\Tabs::make()->tabs([

                Forms\Components\Tabs\Tab::make('اطلاعات اصلی')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('کد محصول (SKU)')
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),

                        Forms\Components\TextInput::make('mine_code')
                            ->label('کد معدن')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('origin_country')
                            ->label('کشور استخراج')
                            ->maxLength(5),

                        Forms\Components\Select::make('status')
                            ->label('وضعیت موجودی')
                            ->options([
                                'available'   => 'موجود',
                                'unavailable' => 'ناموجود',
                                'reserved'    => 'رزرو شده',
                                'sold'        => 'فروخته شده',
                            ])
                            ->required()
                            ->default('available'),
                    ]),

                    Forms\Components\Tabs::make('name_translations')->tabs(
                        collect($locales)->map(fn($label, $code) =>
                        Forms\Components\Tabs\Tab::make($label)->schema([
                            Forms\Components\TextInput::make("name.{$code}")
                                ->label('نام محصول')
                                ->required($code === 'fa'),

                            Forms\Components\TextInput::make("slug.{$code}")
                                ->label('Slug'),

                            Forms\Components\Textarea::make("short_description.{$code}")
                                ->label('توضیح کوتاه')
                                ->rows(2),

                            Forms\Components\RichEditor::make("description.{$code}")
                                ->label('توضیحات کامل'),
                        ])
                        )->toArray()
                    )->columnSpanFull(),

                    Forms\Components\Select::make('categories')
                        ->label('دسته‌بندی‌ها')
                        ->relationship('categories', 'name')
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->getTranslation('name', 'fa'))
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('قیمت‌گذاری')->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('قیمت (ریال)')
                            ->numeric()
                            ->prefix('﷼'),

                        Forms\Components\TextInput::make('price_usd')
                            ->label('قیمت (دلار)')
                            ->numeric()
                            ->prefix('$'),

                        Forms\Components\TextInput::make('price_eur')
                            ->label('قیمت (یورو)')
                            ->numeric()
                            ->prefix('€'),
                    ]),

                    Forms\Components\Toggle::make('price_on_request')
                        ->label('قیمت با تماس')
                        ->default(false),
                ]),

                Forms\Components\Tabs\Tab::make('مشخصات فیزیکی')->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('length_cm')
                            ->label('طول (سانتیمتر)')
                            ->numeric()
                            ->suffix('cm'),

                        Forms\Components\TextInput::make('width_cm')
                            ->label('عرض (سانتیمتر)')
                            ->numeric()
                            ->suffix('cm'),

                        Forms\Components\TextInput::make('height_cm')
                            ->label('ارتفاع (سانتیمتر)')
                            ->numeric()
                            ->suffix('cm'),

                        Forms\Components\TextInput::make('weight_kg')
                            ->label('وزن (کیلوگرم)')
                            ->numeric()
                            ->suffix('kg'),

                        Forms\Components\TextInput::make('area_m2')
                            ->label('متراژ (متر مربع)')
                            ->numeric()
                            ->suffix('m²'),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('ویژگی‌ها')->schema([
                    Repeater::make('attributes')
                        ->label('ویژگی‌های محصول')
                        ->relationship()
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('key.fa')
                                    ->label('کلید (فارسی)')
                                    ->required(),

                                Forms\Components\TextInput::make('key.en')
                                    ->label('کلید (انگلیسی)'),

                                Forms\Components\TextInput::make('unit')
                                    ->label('واحد'),

                                Forms\Components\TextInput::make('value.fa')
                                    ->label('مقدار (فارسی)')
                                    ->required(),

                                Forms\Components\TextInput::make('value.en')
                                    ->label('مقدار (انگلیسی)'),

                                Forms\Components\Toggle::make('is_filterable')
                                    ->label('قابل فیلتر')
                                    ->default(false),
                            ]),
                        ])
                        ->addActionLabel('افزودن ویژگی')
                        ->collapsible()
                        ->orderColumn('sort_order')
                        ->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('تصاویر و ویدیو')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('main_image')
                        ->label('تصویر اصلی')
                        ->collection('main_image')
                        ->image()
                        ->imageResizeMode('cover')
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                        ->label('تامبنیل')
                        ->collection('thumbnail')
                        ->image()
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                        ->label('گالری تصاویر')
                        ->collection('gallery')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('videos')
                        ->label('ویدیوها')
                        ->collection('videos')
                        ->acceptedFileTypes(['video/mp4', 'video/webm'])
                        ->multiple()
                        ->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('تنظیمات')->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('ویژه')
                            ->default(false),

                        Forms\Components\Toggle::make('is_new')
                            ->label('جدید')
                            ->default(false),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('ترتیب نمایش')
                            ->numeric()
                            ->default(0),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('سئو')->schema([
                    Forms\Components\Tabs::make('seo_translations')->tabs(
                        collect($locales)->map(fn($label, $code) =>
                        Forms\Components\Tabs\Tab::make($label)->schema([
                            Forms\Components\TextInput::make("meta_title.{$code}")
                                ->label('عنوان متا'),
                            Forms\Components\Textarea::make("meta_description.{$code}")
                                ->label('توضیحات متا')
                                ->rows(2),
                            Forms\Components\TextInput::make("meta_keywords.{$code}")
                                ->label('کلمات کلیدی'),
                        ])
                        )->toArray()
                    )->columnSpanFull(),

                    Forms\Components\FileUpload::make('og_image')
                        ->label('تصویر OG')
                        ->image()
                        ->columnSpanFull(),
                ]),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('main_image')
                    ->label('')
                    ->collection('main_image')
                    ->conversion('thumb')
                    ->width(60)
                    ->height(60),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام محصول')
                    ->getStateUsing(fn($record) => $record->getTranslation('name', 'fa'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'available'   => 'success',
                        'unavailable' => 'gray',
                        'reserved'    => 'warning',
                        'sold'        => 'danger',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'available'   => 'موجود',
                        'unavailable' => 'ناموجود',
                        'reserved'    => 'رزرو',
                        'sold'        => 'فروخته شده',
                    }),

                Tables\Columns\TextColumn::make('price')
                    ->label('قیمت')
                    ->money('IRR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_usd')
                    ->label('قیمت $')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('ویژه')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('بازدید')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->jalaliDate()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'available'   => 'موجود',
                        'unavailable' => 'ناموجود',
                        'reserved'    => 'رزرو',
                        'sold'        => 'فروخته شده',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('ویژه'),

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
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
