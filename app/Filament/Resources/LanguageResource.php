<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LanguageResource\Pages;
use App\Models\Language;
use App\Services\LanguageService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class LanguageResource extends Resource
{
    /**
     * Only SuperUser can manage languages.
     */
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isSuperUser();
    }

    public static function canCreate(): bool   { return static::canAccess(); }
    public static function canEdit($record): bool   { return static::canAccess(); }
    public static function canDelete($record): bool { return static::canAccess(); }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->isSuperUser();
    }

    protected static ?string $model = Language::class;
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('admin.languages');
    }

    public static function getNavigationGroup(): string
    {
        return __('admin.settings');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Name (English)')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Persian'),

                Forms\Components\TextInput::make('native_name')
                    ->label('Native Name')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('فارسی'),

                Forms\Components\TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->maxLength(10)
                    ->placeholder('fa')
                    ->helperText('e.g. fa, en, ar, de'),

                Forms\Components\TextInput::make('locale')
                    ->label('Locale')
                    ->required()
                    ->maxLength(10)
                    ->placeholder('fa_IR')
                    ->helperText('e.g. fa_IR, en_US, ar_SA'),

                Forms\Components\Select::make('direction')
                    ->label('Direction')
                    ->options([
                        'ltr' => 'LTR (Left to Right)',
                        'rtl' => 'RTL (Right to Left)',
                    ])
                    ->required()
                    ->default('ltr'),

                Forms\Components\TextInput::make('flag')
                    ->label('Flag Emoji')
                    ->maxLength(10)
                    ->placeholder('🇮🇷'),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_default')
                    ->label('Default Language')
                    ->helperText('Only one language can be default')
                    ->default(false),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flag')
                    ->label('')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('native_name')
                    ->label('Native Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('direction')
                    ->label('Direction')
                    ->badge()
                    ->color(fn (string $state) => $state === 'rtl' ? 'warning' : 'info'),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->afterStateUpdated(fn () => LanguageService::clearCache()),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\Action::make('generate_lang')
                    ->label('Generate Lang Files')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        Artisan::call('lang:generate --force');
                        LanguageService::clearCache();

                        Notification::make()
                            ->title('Lang files generated successfully.')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(fn () => LanguageService::clearCache()),

                Tables\Actions\DeleteAction::make()
                    ->after(fn () => LanguageService::clearCache()),
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
            'index'  => Pages\ListLanguages::route('/'),
            'create' => Pages\CreateLanguage::route('/create'),
            'edit'   => Pages\EditLanguage::route('/{record}/edit'),
        ];
    }
}
