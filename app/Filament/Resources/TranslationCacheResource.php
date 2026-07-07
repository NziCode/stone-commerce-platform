<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationCacheResource\Pages;
use App\Models\TranslationCache;
use App\Services\LanguageService;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Read-only browser for the machine-translation cache (see App\Services\TranslationService).
 * Entries are only ever written by the translation pipeline itself, so this resource has
 * no create/edit form — deleting a row just forces that string to be re-translated (or
 * re-fetched from the external service) the next time it's needed.
 */
class TranslationCacheResource extends Resource
{
    protected static ?string $model = TranslationCache::class;
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?int $navigationSort = 11;

    public static function getNavigationLabel(): string
    {
        return __('admin.translation_cache');
    }

    public static function getModelLabel(): string
    {
        return __('admin.cached_translation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.translation_cache');
    }

    public static function getNavigationGroup(): string
    {
        return __('admin.settings');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_locale')
                    ->label(__('admin.source_locale'))
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('target_locale')
                    ->label(__('admin.target_locale'))
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('context')
                    ->label(__('admin.context'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('source_text')
                    ->label(__('admin.source_text'))
                    ->searchable()
                    ->limit(60)
                    ->wrap()
                    ->tooltip(fn (TranslationCache $record) => $record->source_text),

                Tables\Columns\TextColumn::make('translated_text')
                    ->label(__('admin.translated_text'))
                    ->searchable()
                    ->limit(60)
                    ->wrap()
                    ->tooltip(fn (TranslationCache $record) => $record->translated_text),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('admin.cached_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source_locale')
                    ->label(__('admin.source_locale'))
                    ->options(fn () => LanguageService::getActive()->pluck('native_name', 'code')),

                Tables\Filters\SelectFilter::make('target_locale')
                    ->label(__('admin.target_locale'))
                    ->options(fn () => LanguageService::getActive()->pluck('native_name', 'code')),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslationCaches::route('/'),
        ];
    }
}
