<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainCategoryResource\Pages;
use App\Filament\Support\RestrictedToAdmins;
use App\Models\MainCategory;
use App\Services\LanguageService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * The main categories of the catalogue (export grade / saw-cut / top-cut): what visitors filter by on
 * the home page and the products page. Administrators only.
 */
class MainCategoryResource extends Resource
{
    use RestrictedToAdmins;

    protected static ?string $model = MainCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $modelLabel = 'دسته اصلی';
    protected static ?string $pluralModelLabel = 'دسته‌های اصلی';
    protected static ?string $navigationLabel = 'دسته‌های اصلی';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.products');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('products');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->label('شناسه (لاتین)')
                ->helperText('در نشانی صفحه استفاده می‌شود (مثل ?group=export) و بعد از ساخت قابل تغییر نیست.')
                ->required()
                ->alphaDash()
                ->maxLength(40)
                ->unique(ignoreRecord: true)
                ->disabled(fn (?MainCategory $record) => $record !== null)
                ->dehydrated(fn (?MainCategory $record) => $record === null),

            Forms\Components\Tabs::make('NameTranslations')->tabs(
                collect(LanguageService::getActive())->map(fn ($lang) => Forms\Components\Tabs\Tab::make($lang->native_name)
                    ->extraAttributes(['dir' => $lang->direction])
                    ->schema([
                        Forms\Components\TextInput::make("name.{$lang->code}")
                            ->label('نام')
                            ->extraInputAttributes(['dir' => $lang->direction])
                            ->required($lang->code === 'fa')
                            ->maxLength(120),
                    ]))->all()
            )->columnSpanFull(),

            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->label('تصویر')
                ->collection('image')
                ->image()
                ->maxSize(6144)
                ->helperText('در اسلایدر صفحهٔ اصلی دیده می‌شود. اگر تصویری نگذارید، تصویر پیش‌فرض همین دسته نمایش داده می‌شود.')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('sort_order')->label('ترتیب نمایش')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->label('فعال')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->label('')->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->getStateUsing(fn (MainCategory $record) => $record->getTranslation('name', 'fa', false) ?: $record->getTranslation('name', 'en', false))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('key')->label('شناسه')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('products_count')->label('سنگ‌ها')->badge()->color('info'),
                Tables\Columns\TextColumn::make('sort_order')->label('ترتیب')->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('فعال'),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make()->label('ویرایش'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->hidden(fn (MainCategory $record) => $record->key === MainCategory::EXPORT || $record->products_count > 0),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMainCategories::route('/'),
        ];
    }
}
