<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.settings');
    }

    public static function getModelLabel(): string
    {
        return __('admin.settings');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.settings');
    }

    protected static ?string $model = Setting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'تنظیمات';
    protected static ?string $modelLabel = 'تنظیم';
    protected static ?string $pluralModelLabel = 'تنظیمات';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('group')
                    ->label('گروه')
                    ->options([
                        'general'  => 'عمومی',
                        'seo'      => 'سئو',
                        'social'   => 'شبکه‌های اجتماعی',
                        'payment'  => 'پرداخت',
                        'smtp'     => 'ایمیل',
                        'sms'      => 'پیامک',
                        'shipping' => 'ارسال',
                        'contact'  => 'تماس',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('key')
                    ->label('کلید')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('type')
                    ->label('نوع')
                    ->options([
                        'string'  => 'متن',
                        'integer' => 'عدد',
                        'boolean' => 'بولین',
                        'json'    => 'JSON',
                        'array'   => 'آرایه',
                    ])
                    ->default('string')
                    ->required()
                    ->live(),

                Forms\Components\Toggle::make('is_public')
                    ->label('عمومی (قابل دسترس در فرانت)')
                    ->default(false),
            ]),

            Forms\Components\Textarea::make('value')
                ->label('مقدار')
                ->rows(4)
                ->columnSpanFull()
                ->visible(fn($get) => in_array($get('type'), ['string', 'json', 'array'])),

            Forms\Components\TextInput::make('value')
                ->label('مقدار')
                ->numeric()
                ->columnSpanFull()
                ->visible(fn($get) => $get('type') === 'integer'),

            Forms\Components\Toggle::make('value')
                ->label('مقدار')
                ->columnSpanFull()
                ->visible(fn($get) => $get('type') === 'boolean'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('گروه')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label('کلید')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('مقدار')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('نوع')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_public')
                    ->label('عمومی')
                    ->boolean(),
            ])
            ->defaultSort('group')
            ->groups(['group'])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('گروه')
                    ->options([
                        'general'  => 'عمومی',
                        'seo'      => 'سئو',
                        'social'   => 'شبکه‌های اجتماعی',
                        'payment'  => 'پرداخت',
                        'smtp'     => 'ایمیل',
                        'sms'      => 'پیامک',
                        'shipping' => 'ارسال',
                        'contact'  => 'تماس',
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
            'index'  => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit'   => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
