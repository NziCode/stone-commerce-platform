<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Support\TranslateFieldsAction;
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
        $options = [0 => __('admin.first_before_all')];

        foreach ($siblings as $sibling) {
            $name = $sibling->getTranslation('name', app()->getLocale(), false)
                ?: $sibling->getTranslation('name', 'en', false)
                    ?: '—';
            $options[$sibling->sort_order] = __('admin.after') . ': ' . $name;
        }

        return $options;
    }

    // ── Form ──────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Basic Info ────────────────────────────────────
            Forms\Components\Section::make(__('admin.basic_info'))
                ->schema([
                    Forms\Components\Select::make('parent_id')
                        ->label(__('admin.parent_category'))
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
                        ->placeholder(__('admin.no_parent')),

                    Forms\Components\Select::make('sort_order')
                        ->label(__('admin.position'))
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
                            return __('admin.total_siblings') . ': ' . $count;
                        }),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('admin.is_active'))
                        ->default(true),
                ])->columns(3),

            // ── Translations (Tabs) ───────────────────────────
            Forms\Components\Section::make(__('admin.content'))
                ->schema([
                    Forms\Components\Actions::make([
                        TranslateFieldsAction::make(
                            fields: [
                                'name' => false,
                                'description' => false,
                                'meta_title' => false,
                                'meta_description' => false,
                            ],
                            slugField: 'slug',
                        ),
                    ])->key('data.translateActions'),

                    Forms\Components\Tabs::make('Translations')
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

                                        Forms\Components\Textarea::make("description.{$lang->code}")
                                            ->label(__('admin.description'))
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make("meta_title.{$lang->code}")
                                            ->label(__('admin.meta_title')),

                                        Forms\Components\Textarea::make("meta_description.{$lang->code}")
                                            ->label(__('admin.meta_description'))
                                            ->rows(2),
                                    ])->columns(2);
                            })->toArray()
                        )
                        ->columnSpanFull(),
                ]),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                        });
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('admin.parent_category'))
                    ->getStateUsing(fn ($record) =>
                    $record->parent
                        ? ($record->parent->getTranslation('name', app()->getLocale(), false)
                        ?: $record->parent->getTranslation('name', 'en', false))
                        : '—'
                    )
                    ->badge()
                    ->color(fn ($record) => $record->parent_id ? 'warning' : 'gray'),

                Tables\Columns\TextColumn::make('children_count')
                    ->label(__('admin.sub_categories'))
                    ->counts('children')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('products_count')
                    ->label(__('admin.products'))
                    ->counts('products')
                    ->badge()
                    ->color('info'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('admin.is_active')),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('admin.sort_order'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('admin.is_active')),

                Tables\Filters\Filter::make('root_only')
                    ->label(__('admin.root_only'))
                    ->query(fn ($query) => $query->whereNull('parent_id'))
                    ->toggle(),
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
                ]),
            ])
            ->defaultSort('sort_order')
            ->groups([
                Tables\Grouping\Group::make('parent_id')
                    ->label(__('admin.parent_category'))
                    ->getTitleFromRecordUsing(fn ($record) =>
                    $record->parent
                        ? (__('admin.parent_category') . ': ' .
                        ($record->parent->getTranslation('name', app()->getLocale(), false)
                            ?: $record->parent->getTranslation('name', 'en', false)))
                        : __('admin.root_categories')
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
