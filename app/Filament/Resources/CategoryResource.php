<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Services\LanguageService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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

    public static function getNavigationGroup(): ?string
    {
        return __('admin.products');
    }

    public static function getModelLabel(): string
    {
        return __('admin.categories');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.categories');
    }

    // ── Helpers ───────────────────────────────────────────────
    private static function getSiblings(?int $parentId, ?int $excludeId = null): \Illuminate\Support\Collection
    {
        return Category::when(
            $parentId,
            fn ($q) => $q->where('parent_id', $parentId),
            fn ($q) => $q->whereNull('parent_id')
        )
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('sort_order')
            ->get();
    }

    private static function buildPositionOptions(\Illuminate\Support\Collection $siblings): array
    {
        $options = [0 => '⬆ First (before all)'];

        foreach ($siblings as $sibling) {
            $name = $sibling->getTranslation('name', app()->getLocale(), false)
                ?: $sibling->getTranslation('name', 'en', false)
                    ?: '—';
            $options[$sibling->sort_order] = "After: {$name}";
        }

        return $options;
    }

    // ── Form ──────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Basic Info ────────────────────────────────────
            Forms\Components\Section::make('Basic Info')
                ->schema([
                    Forms\Components\Select::make('parent_id')
                        ->label('Parent Category')
                        ->options(function () {
                            return Category::whereNull('parent_id')
                                ->orderBy('sort_order')
                                ->get()
                                ->mapWithKeys(fn ($c) => [
                                    $c->id => $c->getTranslation('name', app()->getLocale(), false)
                                        ?: $c->getTranslation('name', 'en', false)
                                            ?: '—'
                                ]);
                        })
                        ->nullable()
                        ->searchable()
                        ->live()
                        ->placeholder('No parent (root category)')
                        ->afterStateUpdated(fn (Set $set) => $set('sort_order', 0)),

                    Forms\Components\Select::make('sort_order')
                        ->label('Position')
                        ->options(function (Get $get, $record) {
                            $parentId  = $get('parent_id') ? (int) $get('parent_id') : null;
                            $excludeId = $record?->id;
                            $siblings  = self::getSiblings($parentId, $excludeId);
                            return self::buildPositionOptions($siblings);
                        })
                        ->default(0)
                        ->live()
                        ->helperText(function (Get $get, $record) {
                            $parentId  = $get('parent_id') ? (int) $get('parent_id') : null;
                            $excludeId = $record?->id;
                            $count     = self::getSiblings($parentId, $excludeId)->count();
                            return "Total siblings: {$count}";
                        }),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])->columns(3),

            // ── Translations (Tabs) ───────────────────────────
            Forms\Components\Section::make('Content')
                ->schema([
                    Forms\Components\Tabs::make('Translations')
                        ->tabs(
                            collect(LanguageService::getActive())->map(function ($lang) {
                                return Forms\Components\Tabs\Tab::make($lang->native_name)
                                    ->schema([

                                        Forms\Components\TextInput::make("name.{$lang->code}")
                                            ->label('Name')
                                            ->required($lang->code === 'fa')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Set $set, ?string $state) use ($lang) {
                                                $set("slug.{$lang->code}", Str::slug($state ?? ''));
                                            }),

                                        Forms\Components\TextInput::make("slug.{$lang->code}")
                                            ->label('Slug')
                                            ->required($lang->code === 'fa')
                                            ->helperText('Auto-generated from name'),

                                        Forms\Components\Textarea::make("description.{$lang->code}")
                                            ->label('Description')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make("meta_title.{$lang->code}")
                                            ->label('Meta Title'),

                                        Forms\Components\Textarea::make("meta_description.{$lang->code}")
                                            ->label('Meta Description')
                                            ->rows(2),
                                    ])->columns(2);
                            })->toArray()
                        )
                        ->columnSpanFull(),
                ]),

            // ── Dynamic Attribute Schema ──────────────────────
            Forms\Components\Section::make('Dynamic Attribute Schema')
                ->description('Define attributes that products in this category can have.')
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

    // ── Table ─────────────────────────────────────────────────
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
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($q) use ($search) {
                            foreach (LanguageService::getLocales() as $locale) {
                                $q->orWhereRaw(
                                    "JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}')) LIKE ?",
                                    ["%{$search}%"]
                                );
                            }
                        });
                    })
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
                    ->color(fn ($record) => $record->parent_id ? 'warning' : 'gray'),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('Sub-categories')
                    ->counts('children')
                    ->badge()
                    ->color('warning'),

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

                Tables\Filters\Filter::make('root_only')
                    ->label('Root categories only')
                    ->query(fn ($query) => $query->whereNull('parent_id'))
                    ->toggle(),
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
            ->defaultSort('sort_order')
            ->groups([
                Tables\Grouping\Group::make('parent_id')
                    ->label('Parent Category')
                    ->getTitleFromRecordUsing(fn ($record) =>
                    $record->parent
                        ? ($record->parent->getTranslation('name', app()->getLocale(), false)
                        ?: $record->parent->getTranslation('name', 'en', false))
                        : 'Root Categories'
                    )
                    ->collapsible(),
            ])
            ->defaultGroup('parent_id')
            ->groupsInDropdownOnDesktop()
            ->paginated(false);
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
