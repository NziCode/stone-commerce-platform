<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.contact_messages');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.management');
    }

    public static function getModelLabel(): string
    {
        return __('admin.contact_messages');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.contact_messages');
    }

    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'مدیریت';
    protected static ?string $modelLabel = 'پیام';
    protected static ?string $pluralModelLabel = 'پیام‌های تماس';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('اطلاعات فرستنده')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')->label('نام')->disabled(),
                    Forms\Components\TextInput::make('email')->label('ایمیل')->disabled(),
                    Forms\Components\TextInput::make('phone')->label('تلفن')->disabled(),
                    Forms\Components\TextInput::make('company')->label('شرکت')->disabled(),
                    Forms\Components\TextInput::make('country')->label('کشور')->disabled(),
                    Forms\Components\TextInput::make('subject')->label('موضوع')->disabled(),
                ]),
                Forms\Components\Textarea::make('message')
                    ->label('پیام')
                    ->disabled()
                    ->rows(5)
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('پاسخ')->schema([
                Forms\Components\Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        'new'      => 'جدید',
                        'read'     => 'خوانده شده',
                        'replied'  => 'پاسخ داده شده',
                        'archived' => 'بایگانی',
                    ]),

                Forms\Components\Textarea::make('admin_reply')
                    ->label('پاسخ ادمین')
                    ->rows(5)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('country')
                    ->label('کشور')
                    ->badge(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('موضوع')
                    ->limit(40),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'new'      => 'danger',
                        'read'     => 'warning',
                        'replied'  => 'success',
                        'archived' => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'new'      => 'جدید',
                        'read'     => 'خوانده شده',
                        'replied'  => 'پاسخ داده شده',
                        'archived' => 'بایگانی',
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
                        'new'      => 'جدید',
                        'read'     => 'خوانده شده',
                        'replied'  => 'پاسخ داده شده',
                        'archived' => 'بایگانی',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('مشاهده/پاسخ')
                    ->mutateRecordDataUsing(function ($record, $data) {
                        $record->markAsRead();
                        return $data;
                    }),
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
            'index' => Pages\ListContactMessages::route('/'),
            'edit'  => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
