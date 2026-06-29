<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributeResource\Pages;
use App\Models\Attribute;
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

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('admin.attributes');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.products');
    }

    public static function getModelLabel(): string
    {
        return __('admin.attributes');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.attributes');
    }

    // ── Helpers ───────────────────────────────────────────────
    private static function typeIcon(string $type): string
    {
        return match ($type) {
            'text'   => 'heroicon-o-pencil',
            'select' => 'heroicon-o-list-bullet',
            'number' => 'heroicon-o-hashtag',
            'bool'   => 'heroicon-o-check-circle',
            default  => 'heroicon-o-question-mark-circle',
        };
    }

    private static function typeColor(string $type): string
    {
        return match ($type) {
            'select' => 'info',
            'number' => 'warning',
            'bool'   => 'success',
            default  => 'gray',
        };
    }

    private static function typeLabel(string $type): string
    {
        return match ($type) {
            'text'   => __('admin.attr_type_text'),
            'select' => __('admin.attr_type_select'),
            'number' => __('admin.attr_type_number'),
            'bool'   => __('admin.attr_type_bool'),
            default  => $type,
        };
    }

    private static function getSiblings(?int $excludeId = null): \Illuminate\Support\Collection
    {
        return Attribute::when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->orderBy('sort_order')
            ->get();
    }

    private static function buildPositionOptions(\Illuminate\Support\Collection $siblings): array
    {
        $options = [0 => __('admin.first_before_all')];

        foreach ($siblings as $sibling) {
            $label = $sibling->getTranslation('label', app()->getLocale(), false)
                ?: $sibling->getTranslation('label', 'en', false)
                    ?: $sibling->key;
            $options[$sibling->sort_order] = __('admin.after') . ': ' . $label;
        }

        return $options;
    }

    // ── Form ──────────────────────────────────────────────────
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make(__('admin.basic_info'))
                ->schema([
                    // ── Type FIRST ──────────────────────────────
                    Forms\Components\Select::make('type')
                        ->label(__('admin.type'))
                        ->options([
                            'text'   => __('admin.attr_type_text'),
                            'select' => __('admin.attr_type_select'),
                            'number' => __('admin.attr_type_number'),
                            'bool'   => __('admin.attr_type_bool'),
                        ])
                        ->required()
                        ->default('text')
                        ->live()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('key')
                        ->label(__('admin.attribute_key_internal'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('color')
                        ->helperText(__('admin.attribute_key_helper'))
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('unit')
                        ->label(__('admin.unit'))
                        ->placeholder('cm, kg, m2...')
                        ->visible(fn (Get $get) => $get('type') === 'number'),

                    // ── Position (like Category) ────────────────
                    Forms\Components\Select::make('sort_order')
                        ->label(__('admin.position'))
                        ->options(function (Get $get, $record) {
                            $excludeId = $record?->id;
                            $siblings  = self::getSiblings($excludeId);
                            return self::buildPositionOptions($siblings);
                        })
                        ->default(0)
                        ->helperText(function (Get $get, $record) {
                            $excludeId = $record?->id;
                            $count     = self::getSiblings($excludeId)->count();
                            return __('admin.total_siblings') . ': ' . $count;
                        }),

                    // ── Number validation ───────────────────────
                    Forms\Components\TextInput::make('min_value')
                        ->label(__('admin.min_value'))
                        ->numeric()
                        ->visible(fn (Get $get) => $get('type') === 'number'),

                    Forms\Components\TextInput::make('max_value')
                        ->label(__('admin.max_value'))
                        ->numeric()
                        ->visible(fn (Get $get) => $get('type') === 'number'),

                    Forms\Components\TextInput::make('step_value')
                        ->label(__('admin.step_value'))
                        ->numeric()
                        ->placeholder('1, 0.5, 0.01...')
                        ->visible(fn (Get $get) => $get('type') === 'number'),

                    // ── Default value (text/number) ─────────────
                    Forms\Components\TextInput::make('default_value')
                        ->label(__('admin.default_value'))
                        ->visible(fn (Get $get) => in_array($get('type'), ['text', 'number']))
                        ->numeric(fn (Get $get) => $get('type') === 'number')
                        ->dehydrated(fn (Get $get) => in_array($get('type'), ['text', 'number'])),

                    // ── Default value (bool) — separate field ───
                    Forms\Components\Toggle::make('default_value_bool')
                        ->label(__('admin.default_value'))
                        ->visible(fn (Get $get) => $get('type') === 'bool')
                        ->afterStateHydrated(function (Forms\Components\Toggle $component, $record) {
                            if ($record) {
                                $component->state((bool) ((int) $record->default_value));
                            }
                        })
                        ->dehydrated(false),

                    Forms\Components\Toggle::make('is_filterable')
                        ->label(__('admin.is_filterable'))
                        ->default(false),

                    Forms\Components\Toggle::make('show_in_product_page')
                        ->label(__('admin.show_in_product_page'))
                        ->default(true),

                    Forms\Components\Toggle::make('show_in_card')
                        ->label(__('admin.show_in_card'))
                        ->helperText(__('admin.show_in_card_hint'))
                        ->default(false),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('admin.is_active'))
                        ->default(true),
                ])->columns(3),

            // ── Group (select existing or create new) ──────────
            Forms\Components\Section::make(__('admin.attribute_group'))
                ->description(__('admin.attribute_group_desc'))
                ->collapsed()
                ->schema([
                    Forms\Components\Select::make('group_key')
                        ->label(__('admin.attribute_group'))
                        ->options(function () {
                            $groups = Attribute::getDistinctGroups();
                            return array_combine($groups, $groups);
                        })
                        ->searchable()
                        ->live()
                        ->createOptionForm([
                            Forms\Components\Tabs::make('NewGroupTranslations')
                                ->tabs(
                                    collect(LanguageService::getActive())->map(function ($lang) {
                                        return Forms\Components\Tabs\Tab::make($lang->native_name)
                                            ->schema([
                                                Forms\Components\TextInput::make("group.{$lang->code}")
                                                    ->label(__('admin.attribute_group'))
                                                    ->required($lang->code === 'fa'),
                                            ]);
                                    })->toArray()
                                )
                                ->columnSpanFull(),
                        ])
                        ->createOptionUsing(function (array $data, Set $set) {
                            $set('group', $data['group'] ?? []);
                            $currentLocale = app()->getLocale();
                            return $data['group'][$currentLocale]
                                ?? array_values($data['group'] ?? [])[0]
                                ?? null;
                        })
                        ->dehydrated(false)
                        ->afterStateHydrated(function (Set $set, Get $get, $record) {
                            if ($record && $record->group) {
                                $locale = app()->getLocale();
                                $set('group_key', $record->getTranslation('group', $locale, false));
                            }
                        }),

                    Forms\Components\Hidden::make('group')
                        ->afterStateHydrated(function (Set $set, Get $get, $record) {
                            if ($record && $record->group) {
                                $set('group', $record->getTranslations('group'));
                            }
                        })
                        ->dehydrateStateUsing(function (Get $get) {
                            $groupKey = $get('group_key');
                            $existing = $get('group');

                            if ($groupKey && is_string($groupKey)) {
                                $locale = app()->getLocale();
                                $match = Attribute::query()
                                    ->whereNotNull('group')
                                    ->get()
                                    ->first(fn ($a) => $a->getTranslation('group', $locale, false) === $groupKey);

                                if ($match) {
                                    return $match->getTranslations('group');
                                }
                            }

                            return is_array($existing) ? $existing : null;
                        }),
                ]),

            // ── Translatable Label ─────────────────────────────
            Forms\Components\Section::make(__('admin.label_per_language'))
                ->schema([
                    Forms\Components\Tabs::make('LabelTranslations')
                        ->tabs(
                            collect(LanguageService::getActive())->map(function ($lang) {
                                return Forms\Components\Tabs\Tab::make($lang->native_name)
                                    ->schema([
                                        Forms\Components\TextInput::make("label.{$lang->code}")
                                            ->label(__('admin.label'))
                                            ->required($lang->code === 'fa')
                                            ->live(onBlur: true),

                                        Forms\Components\Placeholder::make("preview.{$lang->code}")
                                            ->label(__('admin.field_preview'))
                                            ->content(function (Get $get) use ($lang) {
                                                $type  = $get('type') ?? 'text';
                                                $label = $get("label.{$lang->code}") ?: __('admin.label');
                                                $unit  = $get('unit');

                                                if ($type === 'select') {
                                                    $opts = collect($get('options') ?? [])
                                                        ->map(fn ($o) => $o['label'][$lang->code] ?? $o['label']['en'] ?? '')
                                                        ->filter()
                                                        ->implode(', ');
                                                    return "🔽 {$label}: [{$opts}]";
                                                }

                                                return match ($type) {
                                                    'number' => "🔢 {$label}" . ($unit ? " ({$unit})" : '') . ": 0",
                                                    'bool'   => "☑ {$label}: " . __('admin.yes') . ' / ' . __('admin.no'),
                                                    default  => "📝 {$label}: " . __('admin.attr_type_text'),
                                                };
                                            }),
                                    ])->columns(1);
                            })->toArray()
                        )
                        ->columnSpanFull(),
                ]),

            // ── Options (only for select type) ─────────────────
            Forms\Components\Section::make(__('admin.options_for_select'))
                ->description(__('admin.options_desc'))
                ->visible(fn (Get $get) => $get('type') === 'select')
                ->schema([
                    Forms\Components\Repeater::make('options')
                        ->label('')
                        ->schema(
                            collect(LanguageService::getActive())->map(function ($lang) {
                                return Forms\Components\TextInput::make("label.{$lang->code}")
                                    ->label($lang->native_name)
                                    ->required($lang->code === 'fa');
                            })->toArray()
                        )
                        ->columns(count(LanguageService::getActive()))
                        ->addActionLabel(__('admin.add_option'))
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(function (array $state) {
                            $locale = app()->getLocale();
                            return $state['label'][$locale] ?? $state['label']['en'] ?? __('admin.add_option');
                        })
                        ->afterStateUpdated(function (Set $set, Get $get, ?array $state) {
                            if (!is_array($state)) return;

                            $firstLocale = LanguageService::getActive()->first()?->code;
                            $used = [];

                            foreach ($state as $index => $option) {
                                $base = Str::slug($option['label'][$firstLocale] ?? 'option', '_') ?: 'option';
                                $key  = $option['key'] ?? $base;

                                $original = $key;
                                $i = 1;
                                while (in_array($key, $used)) {
                                    $key = $original . '_' . $i++;
                                }
                                $used[] = $key;

                                $state[$index]['key'] = $key;
                            }

                            $set('options', $state);
                        }),
                ]),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label(__('admin.type'))
                    ->badge()
                    ->icon(fn (string $state) => self::typeIcon($state))
                    ->formatStateUsing(fn (string $state) => self::typeLabel($state))
                    ->color(fn (string $state) => self::typeColor($state)),

                Tables\Columns\TextColumn::make('key')
                    ->label(__('admin.attribute_key_internal'))
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('label')
                    ->label(__('admin.label'))
                    ->getStateUsing(fn ($record) =>
                    $record->getTranslation('label', app()->getLocale(), false)
                        ?: $record->getTranslation('label', 'en', false)
                        ?: '—'
                    )
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->search($search)),

                Tables\Columns\TextColumn::make('group')
                    ->label(__('admin.attribute_group'))
                    ->getStateUsing(fn ($record) =>
                    $record->group
                        ? ($record->getTranslation('group', app()->getLocale(), false)
                        ?: $record->getTranslation('group', 'en', false))
                        : '—'
                    )
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('unit')
                    ->label(__('admin.unit'))
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('usage_count')
                    ->label(__('admin.usage_count'))
                    ->getStateUsing(fn ($record) => $record->productAttributes()->count())
                    ->badge()
                    ->color('primary'),

                Tables\Columns\IconColumn::make('translation_complete')
                    ->label(__('admin.translations'))
                    ->getStateUsing(fn ($record) =>
                        $record->isTranslationComplete('label') && $record->isTranslationComplete('options')
                    )
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->tooltip(fn ($record) =>
                    ($record->isTranslationComplete('label') && $record->isTranslationComplete('options'))
                        ? __('admin.translation_complete')
                        : __('admin.translation_incomplete')
                    ),

                Tables\Columns\IconColumn::make('is_filterable')
                    ->label(__('admin.is_filterable'))
                    ->boolean(),

                Tables\Columns\ToggleColumn::make('show_in_card')
                    ->label(__('admin.show_in_card')),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('admin.is_active')),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('admin.sort_order'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('admin.is_active')),

                Tables\Filters\TernaryFilter::make('is_filterable')
                    ->label(__('admin.is_filterable')),

                Tables\Filters\TernaryFilter::make('show_in_product_page')
                    ->label(__('admin.show_in_product_page')),

                Tables\Filters\TernaryFilter::make('show_in_card')
                    ->label(__('admin.show_in_card')),

                Tables\Filters\SelectFilter::make('type')
                    ->label(__('admin.type'))
                    ->options([
                        'text'   => __('admin.attr_type_text'),
                        'select' => __('admin.attr_type_select'),
                        'number' => __('admin.attr_type_number'),
                        'bool'   => __('admin.attr_type_bool'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('admin.edit')),

                Tables\Actions\DeleteAction::make()
                    ->label(__('admin.delete'))
                    ->requiresConfirmation()
                    ->modalDescription(fn (Attribute $record) =>
                    $record->usage_count > 0
                        ? __('admin.attribute_delete_warning', ['count' => $record->usage_count])
                        : null
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('admin.delete')),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit'   => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }
}
