<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Filament\Support\TranslateFieldsAction;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

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
        return __('admin.event');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.events');
    }

    /** Number of exhibitions that are running or coming up. */
    public static function getNavigationBadge(): ?string
    {
        $count = Event::query()->whereIn('status', ['ongoing', 'upcoming'])->count();

        return $count > 0 ? (string) $count : null;
    }

    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'محتوا';
    protected static ?string $modelLabel = 'نمایشگاه';
    protected static ?string $pluralModelLabel = 'نمایشگاه‌ها';
    protected static ?int $navigationSort = 2;

    private const STATUS_OPTIONS = [
        'upcoming'  => 'پیش‌رو',
        'ongoing'   => 'در حال برگزاری',
        'finished'  => 'برگزار شده',
        'cancelled' => 'لغو شده',
    ];

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية', 'zh' => '中文', 'tr' => 'Türkçe'];

        return $form->schema([
            Forms\Components\Tabs::make()->tabs([

                Forms\Components\Tabs\Tab::make('اطلاعات اصلی')->schema([
                    Forms\Components\Actions::make([
                        TranslateFieldsAction::make(
                            fields: [
                                'title'          => false,
                                'description'    => true,
                                'location'       => false,
                                'organizer_name' => false,
                                'date_label'     => false,
                            ],
                            slugField: 'slug',
                            slugSourceField: 'title',
                        ),
                    ])->key('data.translateActionsMain'),

                    Forms\Components\Tabs::make('translations')->tabs(
                        collect($locales)->map(fn($label, $code) =>
                        Forms\Components\Tabs\Tab::make($label)->schema([
                            Forms\Components\TextInput::make("title.{$code}")
                                ->label('عنوان')
                                ->required($code === 'fa'),

                            Forms\Components\TextInput::make("slug.{$code}")
                                ->label('Slug')
                                ->helperText('آدرس صفحه (فقط حروف انگلیسی و خط تیره). اگر خالی بماند از روی عنوان ساخته می‌شود.'),

                            Forms\Components\RichEditor::make("description.{$code}")
                                ->label('توضیحات')
                                ->toolbarButtons(['bold', 'italic', 'underline', 'h2', 'h3', 'bulletList', 'orderedList', 'blockquote', 'link', 'undo', 'redo'])
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make("location.{$code}")
                                ->label('محل برگزاری')
                                ->helperText('مثال: سایت دائمی نمایشگاهی نیمور، محلات'),

                            Forms\Components\TextInput::make("organizer_name.{$code}")
                                ->label('برگزارکننده'),

                            Forms\Components\TextInput::make("date_label.{$code}")
                                ->label('متن زمان برگزاری (اختیاری)')
                                ->helperText('اگر پر شود، به‌جای تاریخ دقیق در سایت نمایش داده می‌شود؛ مثال: «مهر ۱۴۰۵ — تاریخ دقیق به‌زودی اعلام می‌شود». برای نمایشگاه‌های دارای تاریخ، خالی بگذارید تا تاریخ به‌صورت خودکار (شمسی در فارسی) نوشته شود.'),
                        ])
                        )->toArray()
                    )->columnSpanFull(),
                ]),

                Forms\Components\Tabs\Tab::make('زمان و وضعیت')->schema([
                    Forms\Components\Section::make('انتشار')
                        ->description('کنترل نمایش نمایشگاه در سایت و نحوهٔ تعیین وضعیت آن')
                        ->schema([
                            Forms\Components\Toggle::make('is_published')
                                ->label('نمایش در سایت')
                                ->helperText('با خاموش کردن، نمایشگاه به‌صورت پیش‌نویس درمی‌آید و در سایت دیده نمی‌شود.')
                                ->default(true),

                            Forms\Components\Toggle::make('auto_status')
                                ->label('تغییر خودکار وضعیت بر اساس تاریخ')
                                ->helperText('اگر روشن باشد، سیستم هر ساعت وضعیت را بر اساس تاریخ شروع و پایان به‌روز می‌کند (پیش‌رو ← در حال برگزاری ← برگزار شده). برای نمایشگاهی که تاریخ دقیق ندارد خودکار عمل نمی‌کند و وضعیت را خودتان تعیین کنید.')
                                ->default(true),

                            Forms\Components\Select::make('status')
                                ->label('وضعیت')
                                ->options(self::STATUS_OPTIONS)
                                ->default('upcoming')
                                ->required(),
                        ])->columns(1),

                    Forms\Components\Section::make('زمان و مکان')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DateTimePicker::make('starts_at')
                                ->label('تاریخ شروع')
                                ->seconds(false)
                                ->helperText('اختیاری — اگر تاریخ دقیق مشخص نیست خالی بگذارید و «متن زمان برگزاری» را در تب اطلاعات اصلی بنویسید.'),

                            Forms\Components\DateTimePicker::make('ends_at')
                                ->label('تاریخ پایان')
                                ->seconds(false)
                                ->afterOrEqual('starts_at'),

                            Forms\Components\TextInput::make('city')
                                ->label('شهر'),

                            Forms\Components\TextInput::make('country')
                                ->label('کشور (کد)')
                                ->maxLength(5)
                                ->placeholder('IR'),

                            Forms\Components\TextInput::make('website_url')
                                ->label('وبسایت نمایشگاه')
                                ->url()
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('booth_number')
                                ->label('شماره غرفه'),

                            Forms\Components\TextInput::make('hall_number')
                                ->label('شماره سالن'),
                        ]),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('رسانه‌ها')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                        ->label('تصویر کاور')
                        ->collection('cover')
                        ->image()
                        ->imageEditor()
                        ->helperText('تصویر شاخص نمایشگاه در لیست و بالای صفحه. اگر خالی باشد، اولین عکس گالری استفاده می‌شود و اگر گالری هم خالی باشد یک تصویر پیش‌فرض نمایش داده می‌شود.')
                        ->columnSpanFull(),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                        ->label('گالری تصاویر')
                        ->collection('gallery')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->openable()
                        ->downloadable()
                        ->maxSize(10240)
                        ->helperText('چند عکس را یکجا بکشید و رها کنید و بعد از ذخیره، در بخش «شرح تصاویر گالری» پایین همین صفحه برای هر عکس توضیح چندزبانه بنویسید.')
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
                    Forms\Components\Actions::make([
                        TranslateFieldsAction::make(fields: [
                            'meta_title' => false,
                            'meta_description' => false,
                        ]),
                    ])->key('data.translateActionsSeo'),

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
            ->modifyQueryUsing(fn (Builder $query) => $query->with('media')->withCount([
                'media as gallery_count' => fn (Builder $q) => $q->where('collection_name', 'gallery'),
            ]))
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover')
                    ->label('')
                    ->collection('cover')
                    ->conversion('thumb')
                    ->defaultImageUrl(fn () => Event::placeholderUrl())
                    ->width(80)
                    ->height(55),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان')
                    ->getStateUsing(fn($record) => $record->getTranslation('title', 'fa'))
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('date')
                    ->label('زمان برگزاری')
                    ->getStateUsing(fn (Event $record) => $record->dateText('fa'))
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('starts_at', $direction)),

                Tables\Columns\TextColumn::make('city')->label('شهر')->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'upcoming'  => 'info',
                        'ongoing'   => 'success',
                        'finished'  => 'gray',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn($state) => self::STATUS_OPTIONS[$state] ?? $state),

                Tables\Columns\IconColumn::make('auto_status')
                    ->label('خودکار')
                    ->tooltip('وضعیت بر اساس تاریخ خودکار به‌روز می‌شود')
                    ->boolean(),

                Tables\Columns\TextColumn::make('gallery_count')
                    ->label('عکس')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                Tables\Columns\IconColumn::make('has_videos')
                    ->label('ویدیو')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->getMedia('videos')->isNotEmpty())
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('نمایش در سایت'),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('بازدید')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort(
                fn (Builder $query) => $query
                    ->orderByRaw('starts_at IS NULL DESC')
                    ->orderBy('starts_at', 'desc')
            )
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(self::STATUS_OPTIONS),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('نمایش در سایت')
                    ->trueLabel('منتشر شده')
                    ->falseLabel('پیش‌نویس'),

                Tables\Filters\TernaryFilter::make('has_photos')
                    ->label('گالری تصاویر')
                    ->trueLabel('دارای عکس')
                    ->falseLabel('بدون عکس')
                    ->queries(
                        true: fn (Builder $q) => $q->whereHas('media', fn (Builder $m) => $m->where('collection_name', 'gallery')),
                        false: fn (Builder $q) => $q->whereDoesntHave('media', fn (Builder $m) => $m->where('collection_name', 'gallery')),
                        blank: fn (Builder $q) => $q,
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('viewOnSite')
                    ->label('مشاهده در سایت')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Event $record) => route('events.show', $record->getTranslation('slug', 'fa')))
                    ->openUrlInNewTab()
                    ->visible(fn (Event $record) => $record->is_published),
                Tables\Actions\ReplicateAction::make()
                    ->label(__('admin.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->requiresConfirmation()
                    ->modalHeading(__('admin.duplicate_confirm_heading'))
                    ->modalDescription(__('admin.duplicate_confirm_body'))
                    ->modalSubmitActionLabel(__('admin.duplicate'))
                    ->excludeAttributes(['created_at', 'updated_at', 'deleted_at', 'views_count', 'gallery_count', 'media_count'])
                    ->beforeReplicaSaved(function (Event $replica): void {
                        // The copy starts as a hidden draft with unique slugs, so it can
                        // never shadow (or be confused with) the original on the site.
                        $suffix = strtolower(Str::random(4));
                        foreach ($replica->getTranslations('slug') as $locale => $slug) {
                            $replica->setTranslation('slug', $locale, "{$slug}-copy-{$suffix}");
                        }
                        $replica->is_published = false;
                        $replica->views_count = 0;
                    })
                    ->successRedirectUrl(fn (Event $replica) => static::getUrl('edit', ['record' => $replica])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label('نمایش در سایت')
                        ->icon('heroicon-o-eye')
                        ->action(fn (Collection $records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('مخفی کردن (پیش‌نویس)')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn (Collection $records) => $records->each->update(['is_published' => false]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GalleryRelationManager::class,
        ];
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
