<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterResource\Pages;
use App\Models\Newsletter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.newsletters');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.management');
    }

    public static function getModelLabel(): string
    {
        return __('admin.newsletters');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.newsletters');
    }

    protected static ?string $model = Newsletter::class;
    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';
    protected static ?string $navigationGroup = 'مدیریت';
    protected static ?string $modelLabel = 'مشترک';
    protected static ?string $pluralModelLabel = 'خبرنامه';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->nullable(),

                Forms\Components\TextInput::make('country')
                    ->label('کشور')
                    ->maxLength(5),

                Forms\Components\Select::make('language')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية', 'zh' => '中文', 'tr' => 'Türkçe'])
                    ->default('fa'),

                Forms\Components\Toggle::make('is_active')
                    ->label('فعال')
                    ->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),

                Tables\Columns\TextColumn::make('country')
                    ->label('کشور')
                    ->badge(),

                Tables\Columns\TextColumn::make('language')
                    ->label('زبان')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('confirmed_at')
                    ->label('تأیید شده')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('فعال'),
                Tables\Filters\SelectFilter::make('language')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية', 'zh' => '中文', 'tr' => 'Türkçe']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('لغو اشتراک')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->action(fn($records) => $records->each->unsubscribe()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNewsletters::route('/'),
            'create' => Pages\CreateNewsletter::route('/create'),
            'edit'   => Pages\EditNewsletter::route('/{record}/edit'),
        ];
    }
}
