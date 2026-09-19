<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Services\LanguageService;
use App\Services\TranslationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Lists the photos of an exhibition's gallery so each one can get a caption in
 * every language (shown under the photo in the lightbox and used as its alt
 * text), be re-ordered, promoted to cover or removed.
 * New photos are uploaded from the "رسانه‌ها" tab of the form above.
 */
class GalleryRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $title = 'شرح تصاویر گالری';

    protected static ?string $modelLabel = 'تصویر';

    protected static ?string $pluralModelLabel = 'تصاویر';

    /** Refresh after the parent form is saved (photos may have been added there). */
    protected $listeners = ['eventGalleryChanged' => '$refresh'];

    private const LOCALES = [
        'fa' => 'فارسی', 'en' => 'English', 'ar' => 'العربية', 'hi' => 'Hindi',
        'it' => 'Italiano', 'zh' => '中文', 'tr' => 'Türkçe',
    ];

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('captions')->tabs(
                collect(self::LOCALES)->map(fn ($label, $code) =>
                    Forms\Components\Tabs\Tab::make($label)->schema([
                        Forms\Components\Textarea::make("caption.{$code}")
                            ->label('شرح تصویر')
                            ->rows(3)
                            ->maxLength(300),
                    ])
                )->values()->toArray()
            ),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('collection_name', 'gallery'))
            ->reorderable('order_column')
            ->defaultSort('order_column')
            ->paginated([12, 24, 48, 'all'])
            ->defaultPaginationPageOption(24)
            ->emptyStateHeading('هنوز عکسی در گالری نیست')
            ->emptyStateDescription('عکس‌ها را از تب «رسانه‌ها» در فرم بالا آپلود و ذخیره کنید؛ سپس اینجا برای هر کدام شرح بنویسید.')
            ->columns([
                Tables\Columns\ImageColumn::make('thumb')
                    ->label('')
                    ->getStateUsing(fn (Media $record) => $record->hasGeneratedConversion('thumb') ? $record->getUrl('thumb') : $record->getUrl())
                    ->height(64)
                    ->extraImgAttributes(['style' => 'border-radius:8px;object-fit:cover;width:96px']),

                Tables\Columns\TextColumn::make('caption_fa')
                    ->label('شرح (فارسی)')
                    ->getStateUsing(fn (Media $record) => $record->getCustomProperty('caption.fa'))
                    ->placeholder('— بدون شرح —')
                    ->wrap()
                    ->limit(110),

                Tables\Columns\TextColumn::make('translated')
                    ->label('زبان‌ها')
                    ->badge()
                    ->getStateUsing(fn (Media $record) => count(array_filter((array) $record->getCustomProperty('caption', []), 'filled')) . '/' . count(self::LOCALES))
                    ->color(fn (string $state) => explode('/', $state)[0] === (string) count(self::LOCALES) ? 'success' : 'warning'),

                Tables\Columns\TextColumn::make('file_name')
                    ->label('فایل')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('ویرایش شرح')
                    ->modalHeading('ویرایش شرح تصویر')
                    ->mountUsing(fn (Form $form, Media $record) => $form->fill([
                        'caption' => (array) $record->getCustomProperty('caption', []),
                    ]))
                    ->using(function (Media $record, array $data): Media {
                        $record->setCustomProperty(
                            'caption',
                            array_filter($data['caption'] ?? [], fn ($value) => filled($value))
                        );
                        $record->save();

                        return $record;
                    }),

                Tables\Actions\Action::make('translate')
                    ->label('ترجمه خودکار')
                    ->icon('heroicon-o-language')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalDescription('شرح فارسی این تصویر به زبان‌هایی که خالی هستند ترجمه می‌شود؛ شرح‌های نوشته‌شده تغییر نمی‌کنند.')
                    ->action(fn (Media $record) => $this->translateCaptions(collect([$record]))),

                Tables\Actions\Action::make('makeCover')
                    ->label('کاور نمایشگاه')
                    ->icon('heroicon-o-photo')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalDescription('این تصویر به‌عنوان تصویر شاخص نمایشگاه تنظیم می‌شود.')
                    ->action(function (Media $record) {
                        $record->copy($this->getOwnerRecord(), 'cover');

                        Notification::make()->title('تصویر کاور تغییر کرد')->success()->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('translateSelected')
                        ->label('ترجمه خودکار شرح‌ها')
                        ->icon('heroicon-o-language')
                        ->requiresConfirmation()
                        ->modalDescription('برای تصاویر انتخاب‌شده، شرح‌های خالی از روی متن فارسی ترجمه می‌شود. برای تعداد زیاد بهتر است در چند مرحله انجام دهید.')
                        ->action(fn (Collection $records) => $this->translateCaptions($records))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Fill every empty caption language from the Persian caption.
     *
     * @param  iterable<Media>  $medias
     */
    protected function translateCaptions(iterable $medias): void
    {
        $translator = app(TranslationService::class);
        $targets = LanguageService::getActive()->pluck('code')->reject(fn ($code) => $code === 'fa');

        $filled = 0;
        $failed = 0;

        foreach ($medias as $media) {
            $captions = (array) $media->getCustomProperty('caption', []);
            $source = $captions['fa'] ?? null;

            if (blank($source)) {
                continue;
            }

            foreach ($targets as $target) {
                if (filled($captions[$target] ?? null)) {
                    continue;
                }

                $translated = $translator->translate($source, $target, 'fa');

                if ($translated === null) {
                    $failed++;

                    continue;
                }

                $captions[$target] = $translated;
                $filled++;
            }

            $media->setCustomProperty('caption', $captions);
            $media->save();
        }

        Notification::make()
            ->title($failed ? 'ترجمه ناقص انجام شد' : 'ترجمه انجام شد')
            ->body("{$filled} شرح ترجمه شد" . ($failed ? "، {$failed} مورد ناموفق (بعداً دوباره تلاش کنید)" : ''))
            ->color($failed ? 'warning' : 'success')
            ->send();
    }
}
