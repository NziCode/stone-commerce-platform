<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.reviews');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.store');
    }

    public static function getModelLabel(): string
    {
        return __('admin.reviews');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.reviews');
    }

    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'فروشگاه';
    protected static ?string $modelLabel = 'نظر';
    protected static ?string $pluralModelLabel = 'نظرات';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('اطلاعات نظر')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('product_id')
                        ->label('محصول')
                        ->relationship('product', 'name')
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->getTranslation('name', 'fa'))
                        ->searchable()
                        ->disabled(),

                    Forms\Components\TextInput::make('reviewer_name')->label('نام')->disabled(),
                    Forms\Components\TextInput::make('reviewer_email')->label('ایمیل')->disabled(),
                    Forms\Components\TextInput::make('reviewer_country')->label('کشور')->disabled(),
                    Forms\Components\TextInput::make('reviewer_company')->label('شرکت')->disabled(),

                    Forms\Components\TextInput::make('rating')
                        ->label('امتیاز')
                        ->disabled(),
                ]),

                Forms\Components\Textarea::make('comment')
                    ->label('نظر')
                    ->disabled()
                    ->rows(4)
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('تأیید')->schema([
                Forms\Components\Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending'  => 'در انتظار',
                        'approved' => 'تأیید شده',
                        'rejected' => 'رد شده',
                    ])
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('محصول')
                    ->getStateUsing(fn($record) => $record->product?->getTranslation('name', 'fa'))
                    ->limit(30),

                Tables\Columns\TextColumn::make('reviewer_name')
                    ->label('نام')
                    ->searchable(),

                Tables\Columns\TextColumn::make('reviewer_country')
                    ->label('کشور')
                    ->badge(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('امتیاز')
                    ->formatStateUsing(fn($state) => str_repeat('⭐', $state)),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'pending'  => 'در انتظار',
                        'approved' => 'تأیید شده',
                        'rejected' => 'رد شده',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending'  => 'در انتظار',
                        'approved' => 'تأیید شده',
                        'rejected' => 'رد شده',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('تأیید')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->approve(auth()->id()))
                    ->visible(fn($record) => $record->status === 'pending'),

                Tables\Actions\Action::make('reject')
                    ->label('رد')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->reject())
                    ->visible(fn($record) => $record->status === 'pending'),

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
            'index' => Pages\ListReviews::route('/'),
            'edit'  => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
