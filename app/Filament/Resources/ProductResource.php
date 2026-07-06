<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Support\TranslateFieldsAction;
use App\Models\Attribute;
use App\Models\Product;
use App\Services\LanguageService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('admin.products');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.products');
    }

    public static function getModelLabel(): string
    {
        return __('admin.products');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.products');
    }

    // ── Form ──────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('ProductTabs')
                ->tabs([

                    // ── Basic Info ──────────────────────────────
                    Forms\Components\Tabs\Tab::make(__('admin.basic_info'))
                        ->schema([
                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\TextInput::make('sku')
                                        ->label(__('admin.sku'))
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(100),

                                    Forms\Components\Select::make('status')
                                        ->label(__('admin.status'))
                                        ->options([
                                            'available'   => __('admin.available'),
                                            'unavailable' => __('admin.unavailable'),
                                            'reserved'    => __('admin.reserved'),
                                            'sold'        => __('admin.sold'),
                                        ])
                                        ->required()
                                        ->default('available'),

                                    Forms\Components\Select::make('categories')
                                        ->label(__('admin.categories'))
                                        ->relationship('categories', 'id')
                                        ->getOptionLabelFromRecordUsing(fn ($record) =>
                                        $record->getTranslation('name', app()->getLocale(), false)
                                            ?: $record->getTranslation('name', 'en', false)
                                        )
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->columnSpanFull(),
                                ]),

                            Forms\Components\Actions::make([
                                TranslateFieldsAction::make(
                                    fields: [
                                        'name' => false,
                                        'short_description' => false,
                                        'description' => true,
                                    ],
                                    slugField: 'slug',
                                ),
                            ])->key('data.translateActionsMain'),

                            Forms\Components\Tabs::make('NameTranslations')
                                ->tabs(
                                    collect(LanguageService::getActive())->map(function ($lang) {
                                        return Forms\Components\Tabs\Tab::make($lang->native_name)
                                            ->schema([
                                                Forms\Components\TextInput::make("name.{$lang->code}")
                                                    ->label(__('admin.name'))
                                                    ->required($lang->code === 'fa')
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function (Set $set, ?string $state) use ($lang) {
                                                        $set("slug.{$lang->code}", Str::slug($state ?? ''));
                                                    }),

                                                Forms\Components\TextInput::make("slug.{$lang->code}")
                                                    ->label(__('admin.slug'))
                                                    ->required($lang->code === 'fa')
                                                    ->helperText(__('admin.slug_helper')),

                                                Forms\Components\Textarea::make("short_description.{$lang->code}")
                                                    ->label(__('admin.description') . ' (' . __('admin.title') . ')')
                                                    ->rows(2)
                                                    ->columnSpanFull(),

                                                Forms\Components\RichEditor::make("description.{$lang->code}")
                                                    ->label(__('admin.description'))
                                                    ->columnSpanFull(),
                                            ])->columns(2);
                                    })->toArray()
                                )
                                ->columnSpanFull(),
                        ]),

                    // ── Pricing ─────────────────────────────────
                    Forms\Components\Tabs\Tab::make(__('admin.price'))
                        ->schema([
                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\TextInput::make('price')
                                        ->label(__('admin.price') . ' (﷼)')
                                        ->numeric()
                                        ->prefix('﷼'),

                                    Forms\Components\TextInput::make('price_usd')
                                        ->label(__('admin.price') . ' ($)')
                                        ->numeric()
                                        ->prefix('$'),

                                    Forms\Components\TextInput::make('price_eur')
                                        ->label(__('admin.price') . ' (€)')
                                        ->numeric()
                                        ->prefix('€'),
                                ]),

                            Forms\Components\Toggle::make('price_on_request')
                                ->label(__('admin.price_on_request'))
                                ->default(false),
                        ]),

                    // ── Attributes ──────────────────────────────
                    Forms\Components\Tabs\Tab::make(__('admin.attributes'))
                        ->schema([
                            Forms\Components\Repeater::make('attributes')
                                ->label('')
                                ->relationship()
                                ->schema([
                                    Forms\Components\Select::make('attribute_id')
                                        ->label(__('admin.attributes'))
                                        ->options(function () {
                                            return Attribute::active()
                                                ->ordered()
                                                ->get()
                                                ->mapWithKeys(function ($attr) {
                                                    $label = $attr->getTranslation('label', app()->getLocale(), false)
                                                        ?: $attr->getTranslation('label', 'en', false);

                                                    $group = $attr->group
                                                        ? ($attr->getTranslation('group', app()->getLocale(), false)
                                                            ?: $attr->getTranslation('group', 'en', false))
                                                        : null;

                                                    $displayLabel = $group ? "{$group} — {$label}" : $label;

                                                    return [$attr->id => $displayLabel];
                                                });
                                        })
                                        ->searchable()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, Get $get, ?int $state) {
                                            if (!$state) return;

                                            $attribute = Attribute::find($state);
                                            if (!$attribute) return;

                                            // Pre-fill with default value if available
                                            if ($attribute->isBool()) {
                                                $set('value', ['value' => (bool) ((int) $attribute->default_value)]);
                                            } elseif ($attribute->isNumber()) {
                                                $set('value', ['value' => $attribute->default_value]);
                                            } elseif ($attribute->isSelect()) {
                                                $set('value', ['value' => null]);
                                            } else {
                                                $set('value', []);
                                            }
                                        })
                                        ->columnSpan(1),

                                    // ── Value: TEXT (translatable) ──────────
                                    Forms\Components\Grid::make(count(LanguageService::getActive()))
                                        ->schema(
                                            collect(LanguageService::getActive())->map(function ($lang) {
                                                return Forms\Components\TextInput::make("value.{$lang->code}")
                                                    ->label($lang->native_name)
                                                    ->required($lang->code === 'fa');
                                            })->toArray()
                                        )
                                        ->visible(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->isText() ?? false;
                                        })
                                        ->columnSpan(2),

                                    // ── Value: NUMBER ────────────────────────
                                    Forms\Components\TextInput::make('value.value')
                                        ->label(__('admin.value'))
                                        ->numeric()
                                        ->suffix(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->unit;
                                        })
                                        ->minValue(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->min_value;
                                        })
                                        ->maxValue(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->max_value;
                                        })
                                        ->step(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->step_value ?: 'any';
                                        })
                                        ->visible(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->isNumber() ?? false;
                                        })
                                        ->columnSpan(2),

                                    // ── Value: SELECT ────────────────────────
                                    Forms\Components\Select::make('value.value')
                                        ->label(__('admin.value'))
                                        ->options(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->getOptionsForLocale() ?? [];
                                        })
                                        ->visible(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->isSelect() ?? false;
                                        })
                                        ->columnSpan(2),

                                    // ── Value: BOOL ──────────────────────────
                                    Forms\Components\Toggle::make('value.value')
                                        ->label(__('admin.value'))
                                        ->visible(function (Get $get) {
                                            $attr = Attribute::find($get('attribute_id'));
                                            return $attr?->isBool() ?? false;
                                        })
                                        ->columnSpan(2),
                                ])
                                ->columns(3)
                                ->addActionLabel(__('admin.add_attribute'))
                                ->reorderable()
                                ->collapsible()
                                ->orderColumn('sort_order')
                                ->itemLabel(function (array $state) {
                                    $attribute = Attribute::find($state['attribute_id'] ?? null);

                                    if (!$attribute) {
                                        return __('admin.add_attribute');
                                    }

                                    return $attribute->getTranslation('label', app()->getLocale(), false)
                                        ?: $attribute->getTranslation('label', 'en', false);
                                })
                                ->columnSpanFull(),
                        ]),

                    // ── Media ───────────────────────────────────
                    Forms\Components\Tabs\Tab::make(__('admin.gallery'))
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('main_image')
                                ->label(__('admin.image'))
                                ->collection('main_image')
                                ->image()
                                ->imageResizeMode('cover')
                                ->columnSpanFull(),

                            Forms\Components\SpatieMediaLibraryFileUpload::make('thumbnail')
                                ->label(__('admin.image') . ' (Thumbnail)')
                                ->collection('thumbnail')
                                ->image()
                                ->columnSpanFull(),

                            Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                                ->label(__('admin.gallery'))
                                ->collection('gallery')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->columnSpanFull(),

                            Forms\Components\SpatieMediaLibraryFileUpload::make('videos')
                                ->label(__('admin.gallery') . ' (Video)')
                                ->collection('videos')
                                ->acceptedFileTypes(['video/mp4', 'video/webm'])
                                ->multiple()
                                ->columnSpanFull(),
                        ]),

                    // ── Settings ──────────────────────────────────
                    Forms\Components\Tabs\Tab::make(__('admin.settings'))
                        ->schema([
                            Forms\Components\Grid::make(4)
                                ->schema([
                                    Forms\Components\Toggle::make('is_active')
                                        ->label(__('admin.is_active'))
                                        ->default(true),

                                    Forms\Components\Toggle::make('is_featured')
                                        ->label(__('admin.is_featured'))
                                        ->default(false),

                                    Forms\Components\Toggle::make('is_new')
                                        ->label(__('admin.is_new'))
                                        ->default(false),

                                    Forms\Components\TextInput::make('sort_order')
                                        ->label(__('admin.sort_order'))
                                        ->numeric()
                                        ->default(0),
                                ]),
                        ]),

                    // ── SEO ────────────────────────────────────────
                    Forms\Components\Tabs\Tab::make(__('admin.meta_title'))
                        ->schema([
                            Forms\Components\Actions::make([
                                TranslateFieldsAction::make(fields: [
                                    'meta_title' => false,
                                    'meta_description' => false,
                                    'meta_keywords' => false,
                                ]),
                            ])->key('data.translateActionsSeo'),

                            Forms\Components\Tabs::make('SeoTranslations')
                                ->tabs(
                                    collect(LanguageService::getActive())->map(function ($lang) {
                                        return Forms\Components\Tabs\Tab::make($lang->native_name)
                                            ->schema([
                                                Forms\Components\TextInput::make("meta_title.{$lang->code}")
                                                    ->label(__('admin.meta_title')),

                                                Forms\Components\Textarea::make("meta_description.{$lang->code}")
                                                    ->label(__('admin.meta_description'))
                                                    ->rows(2),

                                                Forms\Components\TextInput::make("meta_keywords.{$lang->code}")
                                                    ->label(__('admin.meta_keywords')),
                                            ]);
                                    })->toArray()
                                )
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('og_image')
                                ->label(__('admin.og_image'))
                                ->image()
                                ->columnSpanFull(),
                        ]),

                ])->columnSpanFull(),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────
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
                    ->label(__('admin.name'))
                    ->getStateUsing(fn ($record) =>
                    $record->getTranslation('name', app()->getLocale(), false)
                        ?: $record->getTranslation('name', 'en', false)
                        ?: '—'
                    )
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($q) use ($search) {
                            foreach (LanguageService::getLocales() as $locale) {
                                $q->orWhereRaw(
                                    "JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}')) LIKE ?",
                                    ["%{$search}%"]
                                );
                            }
                            $q->orWhere('sku', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label(__('admin.sku'))
                    ->searchable()
                    ->copyable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('admin.status'))
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'available'   => 'success',
                        'unavailable' => 'gray',
                        'reserved'    => 'warning',
                        'sold'        => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'available'   => __('admin.available'),
                        'unavailable' => __('admin.unavailable'),
                        'reserved'    => __('admin.reserved'),
                        'sold'        => __('admin.sold'),
                        default       => $state,
                    }),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('admin.price') . ' (﷼)')
                    ->money('IRR')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('price_usd')
                    ->label(__('admin.price') . ' ($)')
                    ->money('USD')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label(__('admin.is_featured'))
                    ->boolean(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('admin.is_active')),

                Tables\Columns\TextColumn::make('views_count')
                    ->label(__('admin.views_count'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('admin.sort_order'))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('admin.status'))
                    ->options([
                        'available'   => __('admin.available'),
                        'unavailable' => __('admin.unavailable'),
                        'reserved'    => __('admin.reserved'),
                        'sold'        => __('admin.sold'),
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label(__('admin.is_featured')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('admin.is_active')),

                Tables\Filters\SelectFilter::make('categories')
                    ->label(__('admin.categories'))
                    ->relationship('categories', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) =>
                    $record->getTranslation('name', app()->getLocale(), false)
                        ?: $record->getTranslation('name', 'en', false)
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('admin.edit')),

                Tables\Actions\DeleteAction::make()
                    ->label(__('admin.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('admin.delete')),

                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('admin.activate'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('admin.deactivate'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
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
