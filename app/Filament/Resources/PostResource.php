<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.posts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.content');
    }

    public static function getModelLabel(): string
    {
        return __('admin.posts');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.posts');
    }

    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'محتوا';
    protected static ?string $modelLabel = 'خبر';
    protected static ?string $pluralModelLabel = 'اخبار';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية', 'zh' => '中文', 'tr' => 'Türkçe'];

        return $form->schema([
            Forms\Components\Tabs::make()->tabs([

                Forms\Components\Tabs\Tab::make('محتوا')->schema([
                    Forms\Components\Tabs::make('translations')->tabs(
                        collect($locales)->map(fn($label, $code) =>
                        Forms\Components\Tabs\Tab::make($label)->schema([
                            Forms\Components\TextInput::make("title.{$code}")
                                ->label('عنوان')
                                ->required($code === 'fa'),

                            Forms\Components\TextInput::make("slug.{$code}")
                                ->label('Slug'),

                            Forms\Components\Textarea::make("excerpt.{$code}")
                                ->label('خلاصه')
                                ->rows(3),

                            Forms\Components\RichEditor::make("content.{$code}")
                                ->label('محتوا'),
                        ])
                        )->toArray()
                    )->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('تنظیمات')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'draft'     => 'پیش‌نویس',
                                'published' => 'منتشر شده',
                                'archived'  => 'بایگانی',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('تاریخ انتشار'),

                        Forms\Components\TextInput::make('reading_time')
                            ->label('زمان مطالعه (دقیقه)')
                            ->numeric(),

                        Forms\Components\Select::make('user_id')
                            ->label('نویسنده')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->default(auth()->id()),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover')
                    ->label('')
                    ->collection('cover')
                    ->conversion('thumb')
                    ->width(80)
                    ->height(55),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان')
                    ->getStateUsing(fn($record) => $record->getTranslation('title', 'fa'))
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('نویسنده'),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        'archived'  => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'published' => 'منتشر شده',
                        'draft'     => 'پیش‌نویس',
                        'archived'  => 'بایگانی',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('تاریخ انتشار')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('بازدید')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'draft'     => 'پیش‌نویس',
                        'published' => 'منتشر شده',
                        'archived'  => 'بایگانی',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('publish')
                    ->label('انتشار')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->publish())
                    ->visible(fn($record) => $record->status === 'draft'),
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
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
