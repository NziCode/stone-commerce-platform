<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.events');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.content');
    }

    public static function getModelLabel(): string
    {
        return __('admin.events');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.events');
    }

    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'محتوا';
    protected static ?string $modelLabel = 'نمایشگاه';
    protected static ?string $pluralModelLabel = 'نمایشگاه‌ها';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية'];

        return $form->schema([
            Forms\Components\Tabs::make()->tabs([

                Forms\Components\Tabs\Tab::make('اطلاعات اصلی')->schema([
                    Forms\Components\Tabs::make('translations')->tabs(
                        collect($locales)->map(fn($label, $code) =>
                        Forms\Components\Tabs\Tab::make($label)->schema([
                            Forms\Components\TextInput::make("title.{$code}")
                                ->label('عنوان')
                                ->required($code === 'fa'),

                            Forms\Components\TextInput::make("slug.{$code}")
                                ->label('Slug'),

                            Forms\Components\Textarea::make("description.{$code}")
                                ->label('توضیحات')
                                ->rows(4),

                            Forms\Components\TextInput::make("location.{$code}")
                                ->label('مکان برگزاری'),
                        ])
                        )->toArray()
                    )->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('تنظیمات')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('تاریخ شروع')
                            ->required(),

                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('تاریخ پایان')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('وضعیت')
                            ->options([
                                'upcoming'  => 'آینده',
                                'ongoing'   => 'در حال برگزاری',
                                'finished'  => 'پایان یافته',
                                'cancelled' => 'لغو شده',
                            ])
                            ->default('upcoming')
                            ->required(),

                        Forms\Components\TextInput::make('city')
                            ->label('شهر'),

                        Forms\Components\TextInput::make('country')
                            ->label('کشور')
                            ->maxLength(5),

                        Forms\Components\TextInput::make('website_url')
                            ->label('وبسایت نمایشگاه')
                            ->url(),

                        Forms\Components\TextInput::make('booth_number')
                            ->label('شماره غرفه'),

                        Forms\Components\TextInput::make('hall_number')
                            ->label('شماره سالن'),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('رسانه‌ها')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                        ->label('تصویر کاور')
                        ->collection('cover')
                        ->image()
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                        ->label('گالری تصاویر')
                        ->collection('gallery')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('videos')
                        ->label('ویدیوها')
                        ->collection('videos')
                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])
                        ->multiple()
                        ->reorderable()
                        ->maxSize(102400)
                        ->helperText('فرمت‌های مجاز: MP4، WebM، MOV — حداکثر حجم هر فایل ۱۰۰ مگابایت')
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

                Tables\Columns\TextColumn::make('city')->label('شهر'),

                Tables\Columns\TextColumn::make('country')
                    ->label('کشور')
                    ->badge(),

                Tables\Columns\IconColumn::make('has_videos')
                    ->label('ویدیو')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->getMedia('videos')->isNotEmpty()),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('شروع')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label('پایان')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'upcoming'  => 'info',
                        'ongoing'   => 'success',
                        'finished'  => 'gray',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'upcoming'  => 'آینده',
                        'ongoing'   => 'در حال برگزاری',
                        'finished'  => 'پایان یافته',
                        'cancelled' => 'لغو شده',
                    }),

                Tables\Columns\TextColumn::make('booth_number')
                    ->label('غرفه'),
            ])
            ->defaultSort('starts_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'upcoming'  => 'آینده',
                        'ongoing'   => 'در حال برگزاری',
                        'finished'  => 'پایان یافته',
                        'cancelled' => 'لغو شده',
                    ]),
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
            'index'  => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit'   => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
