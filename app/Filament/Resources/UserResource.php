<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('admin.users');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.users');
    }

    public static function getModelLabel(): string
    {
        return __('admin.users');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.users');
    }

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'مدیریت';
    protected static ?string $modelLabel = 'کاربر';
    protected static ?string $pluralModelLabel = 'کاربران';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('اطلاعات اصلی')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('نام')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('ایمیل')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('phone')
                        ->label('موبایل')
                        ->maxLength(20),

                    Forms\Components\TextInput::make('company')
                        ->label('شرکت')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('country')
                        ->label('کشور')
                        ->maxLength(5),

                    Forms\Components\Select::make('locale')
                        ->label('زبان')
                        ->options(['fa' => 'فارسی', 'en' => 'English', 'hi' => 'Hindi', 'it' => 'Italiano', 'ar' => 'العربية'])
                        ->default('fa'),

                    Forms\Components\TextInput::make('password')
                        ->label('رمز عبور')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $context) => $context === 'create'),

                    Forms\Components\Select::make('roles')
                        ->label('نقش‌ها')
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->preload(),
                ]),
            ]),

            Forms\Components\Section::make('تنظیمات')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('فعال')
                        ->default(true),

                    Forms\Components\Toggle::make('email_verified_at')
                        ->label('ایمیل تأیید شده')
                        ->dehydrateStateUsing(fn($state) => $state ? now() : null)
                        ->afterStateHydrated(fn($component, $state) => $component->state(filled($state))),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->label('')
                    ->collection('avatar')
                    ->conversion('thumb')
                    ->circular()
                    ->width(40)
                    ->height(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('موبایل')
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('نقش')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('company')
                    ->label('شرکت')
                    ->searchable(),

                Tables\Columns\TextColumn::make('country')
                    ->label('کشور')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('آخرین ورود')
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

                Tables\Filters\SelectFilter::make('roles')
                    ->label('نقش')
                    ->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('ban')
                    ->label('مسدود کردن')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->update(['is_active' => false]))
                    ->visible(fn($record) => $record->is_active),
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
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
