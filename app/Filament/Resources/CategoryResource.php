<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Services\LanguageService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('admin.categories');
    }

    public static function getNavigationGroup(): string
    {
        return __('admin.products');
    }

    public static function form(Form $form): Form
    {
        $locales = LanguageService::getLocales();

        return $form->schema([

            Forms\Components\Section::make('Basic Info')
                ->schema([
                    Forms\Components\Select::make('parent_id')
                        ->label('Parent Category')
                        ->options(
                            Category::whereNull('parent_id')
                                ->get()
                                ->mapWithKeys(fn ($c) => [
                                    $c->id => $c->getTranslation('name', app()->getLocale(), false)
                                        ?: $c->getTranslation('name', 'en', false)
                                            ?: '—'
                                ])
                        )
                        ->nullable()
                        ->searchable()
                        ->placeholder('No parent (root category)'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])->columns(3),

            // ── نام و slug برای هر زبان ──────────────────────
            Forms\Components\Section::make('Name & Slug')
                ->schema(
                    collect($locales)->flatMap(function ($locale) {
                        return [
                            Forms\Components\TextInput::make("name.{$locale}")
                                ->label("Name ({$locale})")
                                ->required($locale === 'fa')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state) use ($locale) {
                                    $set("slug.{$locale}", Str::slug($state ?? ''));
                                }),

                            Forms\Components\TextInput::make("slug.{$locale}")
                                ->label("Slug ({$locale})")
                                ->required($locale === 'fa'),
                        ];
                    })->toArray()
                )->columns(2),

            // ── توضیحات ───────────────────────────────────────
            Forms\Components\Section::make('Description')
                ->schema(
                    collect($locales)->map(fn ($locale) =>
                    Forms\Components\Textarea::make("description.{$locale}")
                        ->label("Description ({$locale})")
                        ->rows(3)
                    )->toArray()
                )->columns(2)->collapsed(),

            // ── SEO ───────────────────────────────────────────
            Forms\Components\Section::make('SEO')
                ->schema(
                    collect($locales)->flatMap(fn ($locale) => [
                        Forms\Components\TextInput::make("meta_title.{$locale}")
                            ->label("Meta Title ({$locale})"),
                        Forms\Components\Textarea::make("meta_description.{$locale}")
                            ->label("Meta Description ({$locale})")
                            ->rows(2),
                    ])->toArray()
                )->columns(2)->collapsed(),

            // ── ویژگی‌های دینامیک دسته ────────────────────────
            Forms\Components\Section::make('Dynamic Attribute Schema')
                ->helperText('Define attributes that products in this category can have.')
                ->schema([
                    Forms\Components\Repeater::make('attribute_schema')
                        ->label('')
                        ->schema([
                            Forms\Components\TextInput::make('key')
                                ->label('Key (internal)')
                                ->required()
                                ->placeholder('color'),

                            Forms\Components\Select::make('type')
                                ->label('Type')
                                ->options([
                                    'text'   => 'Text',
                                    'select' => 'Select (dropdown)',
                                    'number' => 'Number',
                                    'bool'   => 'Yes/No',
                                ])
                                ->required()
                                ->default('text'),

                            Forms\Components\KeyValue::make('label')
                                ->label('Label per language')
                                ->keyLabel('Locale')
                                ->valueLabel('Label'),

                            Forms\Components\TagsInput::make('options')
                                ->label('Options (for select type)')
                                ->placeholder('Add option'),
                        ])
                        ->columns(2)
                        ->addActionLabel('Add Attribute')
                        ->collapsible(),
                ])->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->getStateUsing(fn ($record) =>
                    $record->getTranslation('name', app()->getLocale(), false)
                        ?: $record->getTranslation('name', 'en', false)
                        ?: '—'
                    )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->getStateUsing(fn ($record) =>
                    $record->parent
                        ? ($record->parent->getTranslation('name', app()->getLocale(), false)
                        ?: $record->parent->getTranslation('name', 'en', false))
                        : '—'
                    )
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->badge()
                    ->color('info'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
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
