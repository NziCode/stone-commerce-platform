<?php

namespace App\Filament\Support;

use App\Models\Product;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables;

/**
 * "Record sale" / "Cancel sale" table actions, shared by the product list and the dashboard
 * so a stone can be marked sold (with price, buyer, date) from either place.
 */
class StoneSaleActions
{
    public const CURRENCIES = ['USD' => 'USD ($)', 'EUR' => 'EUR (€)', 'AED' => 'AED', 'IRT' => 'تومان', 'IRR' => 'ریال'];

    public static function allowed(): bool
    {
        $user = auth()->user();

        return $user && ($user->isAdmin() || $user->isSuperUser());
    }

    /** The form asking for the sale details (also used by the reservation "final payment" action). */
    public static function saleForm(string $defaultCurrency = 'USD', ?string $defaultBuyer = null): array
    {
        return [
            Forms\Components\TextInput::make('sold_price')->label('مبلغ فروش')->numeric()->minValue(0),
            Forms\Components\Select::make('sold_currency')->label('واحد پول')->options(self::CURRENCIES)->default($defaultCurrency),
            Forms\Components\TextInput::make('sold_to')->label('خریدار')->default($defaultBuyer)->maxLength(191),
        ];
    }

    /** Only the sale fields, empty strings turned into null. */
    public static function saleData(array $data): array
    {
        return [
            'sold_price'    => filled($data['sold_price'] ?? null) ? $data['sold_price'] : null,
            'sold_currency' => filled($data['sold_price'] ?? null) ? ($data['sold_currency'] ?? null) : null,
            'sold_to'       => filled($data['sold_to'] ?? null) ? $data['sold_to'] : null,
        ] + (filled($data['sold_at'] ?? null) ? ['sold_at' => $data['sold_at']] : []);
    }

    public static function recordSale(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('recordSale')
            ->label('ثبت فروش')
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->visible(fn (Product $record) => static::allowed() && $record->status !== 'sold')
            ->modalHeading('ثبت فروش سنگ')
            ->modalDescription('پس از ثبت، وضعیت سنگ «فروخته‌شده» می‌شود و در گزارش‌های انبار، مالک و معدن حساب می‌شود.')
            ->form([
                Forms\Components\DateTimePicker::make('sold_at')->label('تاریخ فروش')->seconds(false)->default(fn () => now())->required(),
                ...static::saleForm(),
            ])
            ->action(function (Product $record, array $data) {
                $record->markAsSold(static::saleData($data));

                Notification::make()->title('فروش ثبت شد')->success()->send();
            });
    }

    public static function cancelSale(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('cancelSale')
            ->label('لغو فروش')
            ->icon('heroicon-o-arrow-uturn-left')
            ->color('gray')
            ->visible(fn (Product $record) => static::allowed() && $record->status === 'sold')
            ->requiresConfirmation()
            ->modalHeading('لغو فروش')
            ->modalDescription('سنگ دوباره «موجود» می‌شود و اطلاعات فروش (مبلغ، خریدار، تاریخ) پاک می‌شود.')
            ->action(function (Product $record) {
                $record->markAsAvailable();

                Notification::make()->title('فروش لغو شد؛ سنگ دوباره موجود است')->success()->send();
            });
    }
}
