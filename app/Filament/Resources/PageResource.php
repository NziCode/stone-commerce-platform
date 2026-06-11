<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.pages');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.content');
    }

    public static function getModelLabel(): string
    {
        return __('admin.pages');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.pages');
    }

    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'محتوا';
    protected static ?string $modelLabel = 'صفحه';
    protected static ?string $pluralModelLabel = 'صفحات';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية'];

        return $form->schema([
            Forms\Components\Tabs::make()->tabs([

                Forms\Components\Tabs\Tab::make('محتوا')->schema([
                    Forms\Components\Tabs::make('translations')->tabs(
                        collect($locales)->map(fn($label, $code) =>
                        Forms\Components\Tabs\Tab::make($label)->schema([
                            Forms\Components\TextInput::make("title.{$code}")
                                ->label('عنوان صفحه')
                                ->required($code === 'fa'),

                            Forms\Components\TextInput::make("slug.{$code}")
                                ->label('Slug'),

                            Forms\Components\Textarea::make("excerpt.{$code}")
                                ->label('خلاصه')
                                ->rows(2),

                            Forms\Components\RichEditor::make("content.{$code}")
                                ->label('محتوا'),
                        ])
                        )->toArray()
                    )->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('تنظیمات')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('template')
                            ->label('قالب')
                            ->options([
                                'default'    => 'پیش‌فرض',
                                'full-width' => 'تمام عرض',
                                'sidebar'    => 'با سایدبار',
                                'contact'    => 'تماس با ما',
                                'about'      => 'درباره ما',
                            ])
                            ->default('default')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('تصاویر')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                        ->label('تصویر کاور')
                        ->collection('cover')
                        ->image()
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                        ->label('گالری')
                        ->collection('gallery')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('سئو')->schema([
                    Forms\Components\Tabs::make('seo_translations')->tabs(
                        collect($locales)->map(fn($label, $code) =>
                        Forms\Components\Tabs\Tab::make($label)->schema([
                            Forms\Components\TextInput::make("meta_title.{$code}")
                                ->label('عنوان متا'),
                            Forms\Components\Textarea::make("meta_description.{$code}")
                                ->label('توضیحات متا')
                                ->rows(2),
                            Forms\Components\TextInput::make("meta_keywords.{$code}")
                                ->label('کلمات کلیدی'),
                        ])
                        )->toArray()
                    )->columnSpanFull(),

                    Forms\Components\FileUpload::make('og_image')
                        ->label('تصویر OG')
                        ->image()
                        ->columnSpanFull(),
                ]),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان')
                    ->getStateUsing(fn($record) => $record->getTranslation('title', 'fa'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('template')
                    ->label('قالب')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('بازدید')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخرین ویرایش')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
