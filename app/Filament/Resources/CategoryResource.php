<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'فروشگاه';
    protected static ?string $modelLabel = 'دسته‌بندی';
    protected static ?string $pluralModelLabel = 'دسته‌بندی‌ها';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية'];

        return $form->schema([
            Forms\Components\Tabs::make()->tabs([

                Forms\Components\Tabs\Tab::make('اطلاعات اصلی')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('دسته والد')
                            ->relationship('parent', 'name')
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->getTranslation('name', 'fa'))
                            ->searchable()
                            ->nullable()
                            ->columnSpanFull(),
                    ]),

                    Forms\Components\Tabs::make('translations')->tabs(
                        collect($locales)->map(fn($label, $code) =>
                        Forms\Components\Tabs\Tab::make($label)->schema([
                            Forms\Components\TextInput::make("name.{$code}")
                                ->label('نام دسته')
                                ->required($code === 'fa'),

                            Forms\Components\TextInput::make("slug.{$code}")
                                ->label('Slug'),

                            Forms\Components\Textarea::make("description.{$code}")
                                ->label('توضیحات')
                                ->rows(3),
                        ])
                        )->toArray()
                    )->columnSpanFull(),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('ترتیب نمایش')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('ویژگی‌های دینامیک')->schema([
                    Repeater::make('attribute_schema')
                        ->label('ویژگی‌های این دسته‌بندی')
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('key')
                                    ->label('کلید')
                                    ->required()
                                    ->helperText('مثال: color'),

                                Forms\Components\TextInput::make('label.fa')
                                    ->label('برچسب فارسی')
                                    ->required(),

                                Forms\Components\TextInput::make('label.en')
                                    ->label('برچسب انگلیسی'),

                                Forms\Components\Select::make('type')
                                    ->label('نوع')
                                    ->options([
                                        'text'   => 'متن',
                                        'select' => 'انتخابی',
                                        'number' => 'عدد',
                                        'color'  => 'رنگ',
                                    ])
                                    ->required()
                                    ->default('text'),

                                Forms\Components\TextInput::make('unit')
                                    ->label('واحد')
                                    ->helperText('مثال: cm, kg'),

                                Forms\Components\Toggle::make('filterable')
                                    ->label('قابل فیلتر')
                                    ->default(false),
                            ]),

                            Forms\Components\TagsInput::make('options')
                                ->label('گزینه‌ها (برای نوع انتخابی)')
                                ->helperText('Enter بزنید تا اضافه شود'),
                        ])
                        ->addActionLabel('افزودن ویژگی')
                        ->collapsible()
                        ->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('تصاویر')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                        ->label('تصویر دسته‌بندی')
                        ->collection('image')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('4:3')
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                        ->label('گالری')
                        ->collection('gallery')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->columnSpanFull(),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->label('')
                    ->collection('image')
                    ->conversion('thumb')
                    ->width(60)
                    ->height(45),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->getStateUsing(fn($record) => $record->getTranslation('name', 'fa'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('دسته والد')
                    ->getStateUsing(fn($record) => $record->parent?->getTranslation('name', 'fa') ?? '—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('محصولات')
                    ->counts('products')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('depth')
                    ->label('سطح')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ترتیب')
                    ->sortable(),
            ])
            ->defaultSort('_lft')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('وضعیت'),
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
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
