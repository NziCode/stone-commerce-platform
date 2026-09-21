<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductInquiryResource\Pages;
use App\Models\ProductInquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Price inquiries left by visitors on the "Request a quote" button of a stone: who asked, about which
 * stone, how to reach them; the sales team calls / messages them and marks the request done.
 */
class ProductInquiryResource extends Resource
{
    protected static ?string $model = ProductInquiry::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return 'استعلام‌های قیمت';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.orders');
    }

    public static function getModelLabel(): string
    {
        return 'استعلام قیمت';
    }

    public static function getPluralModelLabel(): string
    {
        return 'استعلام‌های قیمت';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::canViewAny() ? (static::getModel()::where('status', 'new')->count() ?: null) : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    /** Customers' phone numbers: administrators, the super user and the sales team. */
    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user && ($user->isAdmin() || $user->isSuperUser() || $user->isSales());
    }

    // Requests are only ever created from the storefront.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->columns(2)
                ->schema([
                    Forms\Components\Placeholder::make('stone')->label('سنگ')
                        ->content(fn (?ProductInquiry $record) => $record ? static::stoneLabel($record) : '—'),
                    Forms\Components\Placeholder::make('customer')->label('مشتری')
                        ->content(fn (?ProductInquiry $record) => $record?->name ?: '—'),
                    Forms\Components\Placeholder::make('phone_info')->label('تلفن')
                        ->content(fn (?ProductInquiry $record) => $record?->full_phone ?: '—'),
                    Forms\Components\Placeholder::make('method_info')->label('روش تماس')
                        ->content(fn (?ProductInquiry $record) => $record?->contact_method === 'whatsapp' ? 'واتساپ' : 'تماس تلفنی'),
                    Forms\Components\Placeholder::make('note_info')->label('توضیح مشتری')
                        ->content(fn (?ProductInquiry $record) => $record?->note ?: '—')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Select::make('status')->label('وضعیت')
                ->options(['new' => 'جدید', 'contacted' => 'تماس گرفته شد', 'closed' => 'بسته‌شده'])
                ->required()
                ->native(false),
            Forms\Components\Textarea::make('admin_note')->label('یادداشت داخلی')
                ->helperText('فقط تیم فروش می‌بیند: قیمت گفته‌شده، نتیجهٔ مذاکره …')
                ->rows(3)
                ->maxLength(2000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('زمان')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('product_id')
                    ->label('سنگ')
                    ->formatStateUsing(fn ($state, ProductInquiry $record) => static::stoneLabel($record))
                    ->searchable(query: fn ($query, string $search) => $query->whereHas('product', fn ($q) => $q->where('sku', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%")))
                    ->limit(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('نام')
                    ->placeholder('—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('full_phone')
                    ->label('تلفن')
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->searchable(['phone']),

                Tables\Columns\TextColumn::make('contact_method')
                    ->label('روش تماس')
                    ->badge()
                    ->color(fn (string $state) => $state === 'whatsapp' ? 'success' : 'gray')
                    ->formatStateUsing(fn (string $state) => $state === 'whatsapp' ? 'واتساپ' : 'تماس تلفنی'),

                Tables\Columns\TextColumn::make('note')
                    ->label('توضیح')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'new'       => 'warning',
                        'contacted' => 'info',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state, ProductInquiry $record) => $record->status_label),

                Tables\Columns\TextColumn::make('contacted_at')
                    ->label('زمان تماس')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->label('وضعیت')
                    ->options(['new' => 'جدید', 'contacted' => 'تماس گرفته شد', 'closed' => 'بسته‌شده']),
                Tables\Filters\SelectFilter::make('contact_method')->label('روش تماس')
                    ->options(['call' => 'تماس تلفنی', 'whatsapp' => 'واتساپ']),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('واتساپ به مشتری')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->iconButton()
                    ->url(fn (ProductInquiry $record) => $record->whatsapp_url, shouldOpenInNewTab: true)
                    ->visible(fn (ProductInquiry $record) => filled($record->whatsapp_url)),

                Tables\Actions\Action::make('call')
                    ->label('تماس با مشتری')
                    ->icon('heroicon-o-phone')
                    ->color('gray')
                    ->iconButton()
                    ->url(fn (ProductInquiry $record) => 'tel:' . $record->call_number)
                    ->visible(fn (ProductInquiry $record) => filled($record->call_number)),

                Tables\Actions\Action::make('markContacted')
                    ->label('تماس گرفته شد')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->visible(fn (ProductInquiry $record) => $record->status === 'new')
                    ->action(function (ProductInquiry $record) {
                        $record->markContacted(auth()->id());

                        Notification::make()->title('ثبت شد: با مشتری تماس گرفته شد')->success()->send();
                    }),

                Tables\Actions\Action::make('close')
                    ->label('بستن')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->visible(fn (ProductInquiry $record) => $record->status !== 'closed')
                    ->requiresConfirmation()
                    ->modalDescription('درخواست بسته می‌شود؛ هر زمان از فیلتر وضعیت دوباره قابل مشاهده است.')
                    ->action(function (ProductInquiry $record) {
                        $record->update(['status' => 'closed']);

                        Notification::make()->title('درخواست بسته شد')->success()->send();
                    }),

                Tables\Actions\EditAction::make()->label('وضعیت و یادداشت'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف'),
            ])
            ->emptyStateHeading('هنوز استعلام قیمتی ثبت نشده است')
            ->emptyStateDescription('وقتی بازدیدکننده‌ای روی «استعلام قیمت» یک سنگ شمارهٔ خود را بگذارد، اینجا می‌آید.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProductInquiries::route('/'),
        ];
    }

    private static function stoneLabel(ProductInquiry $record): string
    {
        $product = $record->product;

        if (! $product) {
            return '—';
        }

        $name = $product->getTranslation('name', 'fa', false) ?: $product->getTranslation('name', 'en', false);

        return $name . ($product->sku ? ' (' . $product->sku . ')' : '');
    }
}
