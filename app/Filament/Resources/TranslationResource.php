<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Translation;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return __('admin.translations');
    }

    public static function getNavigationGroup(): string
    {
        return __('admin.settings');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('locale')
                ->label(__('admin.language'))
                ->options(Language::active()->pluck('native_name', 'code'))
                ->required()
                ->searchable(),

            Forms\Components\Select::make('group')
                ->label(__('admin.group'))
                ->options([
                    'admin'    => 'Admin Panel',
                    'messages' => 'Frontend Messages',
                ])
                ->required(),

            Forms\Components\TextInput::make('key')
                ->label(__('admin.key'))
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('value')
                ->label(__('admin.value'))
                ->rows(3)
                ->required(),

            Forms\Components\Toggle::make('is_auto')
                ->label(__('admin.auto_translated'))
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('locale')
                    ->label(__('admin.language'))
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('group')
                    ->label(__('admin.group'))
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label(__('admin.key'))
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('value')
                    ->label(__('admin.value'))
                    ->searchable()
                    ->limit(60)
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_auto')
                    ->label(__('admin.auto_translated'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('admin.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('locale')
                    ->label(__('admin.language'))
                    ->options(Language::active()->pluck('native_name', 'code')),

                Tables\Filters\SelectFilter::make('group')
                    ->label(__('admin.group'))
                    ->options([
                        'admin'    => 'Admin Panel',
                        'messages' => 'Frontend Messages',
                    ]),

                Tables\Filters\TernaryFilter::make('is_auto')
                    ->label(__('admin.auto_translated')),
            ])
            ->headerActions([
                // دکمه تولید فایل‌های lang
                Tables\Actions\Action::make('generate_files')
                    ->label(__('admin.generate_lang_files'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        Artisan::call('lang:generate --force');
                        Translation::clearCache();

                        Notification::make()
                            ->title(__('admin.lang_files_generated'))
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(fn () => Translation::clearCache()),

                Tables\Actions\DeleteAction::make()
                    ->after(fn () => Translation::clearCache()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group')
            ->defaultGroup('group');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit'   => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
