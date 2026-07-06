<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Support\TranslateFieldsAction;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SliderResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.sliders');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.appearance');
    }

    public static function getModelLabel(): string
    {
        return __('admin.sliders');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.sliders');
    }

    protected static ?string $model = Slider::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'ظاهر سایت';
    protected static ?string $modelLabel = 'اسلاید';
    protected static ?string $pluralModelLabel = 'اسلایدر';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $locales = ['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية', 'zh' => '中文', 'tr' => 'Türkçe'];

        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('type')
                        ->label('نوع')
                        ->options(['image' => 'تصویر', 'video' => 'ویدیو'])
                        ->default('image')
                        ->required()
                        ->live(),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('ترتیب نمایش')
                        ->numeric()
                        ->default(0),

                    Forms\Components\TextInput::make('button_link')
                        ->label('لینک دکمه')
                        ->url(),

                    Forms\Components\Select::make('button_target')
                        ->label('هدف لینک')
                        ->options(['_self' => 'همین تب', '_blank' => 'تب جدید'])
                        ->default('_self'),

                    Forms\Components\ColorPicker::make('overlay_color')
                        ->label('رنگ overlay'),

                    Forms\Components\TextInput::make('overlay_opacity')
                        ->label('شفافیت overlay (0-100)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->label('فعال')
                        ->default(true),
                ]),

                Forms\Components\Actions::make([
                    TranslateFieldsAction::make(fields: [
                        'title' => false,
                        'subtitle' => false,
                        'button_text' => false,
                    ]),
                ])->key('data.translateActions'),

                Forms\Components\Tabs::make('translations')->tabs(
                    collect($locales)->map(fn($label, $code) =>
                    Forms\Components\Tabs\Tab::make($label)->schema([
                        Forms\Components\TextInput::make("title.{$code}")
                            ->label('عنوان'),
                        Forms\Components\TextInput::make("subtitle.{$code}")
                            ->label('زیرعنوان'),
                        Forms\Components\TextInput::make("button_text.{$code}")
                            ->label('متن دکمه'),
                    ])
                    )->toArray()
                )->columnSpanFull(),
            ]),

            Forms\Components\Section::make('تصاویر')->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                    ->label('تصویر دسکتاپ')
                    ->collection('image')
                    ->image()
                    ->columnSpanFull()
                    ->visible(fn($get) => $get('type') === 'image'),

                Forms\Components\SpatieMediaLibraryFileUpload::make('mobile_image')
                    ->label('تصویر موبایل')
                    ->collection('mobile_image')
                    ->image()
                    ->columnSpanFull()
                    ->visible(fn($get) => $get('type') === 'image'),

                Forms\Components\SpatieMediaLibraryFileUpload::make('video')
                    ->label('ویدیو')
                    ->collection('video')
                    ->acceptedFileTypes(['video/mp4', 'video/webm'])
                    ->columnSpanFull()
                    ->visible(fn($get) => $get('type') === 'video'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->label('')
                    ->collection('image')
                    ->conversion('mobile')
                    ->width(120)
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان')
                    ->getStateUsing(fn($record) => $record->getTranslation('title', 'fa') ?? '—'),

                Tables\Columns\TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->color(fn($state) => $state === 'image' ? 'info' : 'warning')
                    ->formatStateUsing(fn($state) => $state === 'image' ? 'تصویر' : 'ویدیو'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('ترتیب')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
            'index'  => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit'   => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
