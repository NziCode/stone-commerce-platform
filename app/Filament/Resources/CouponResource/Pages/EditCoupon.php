<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoupon extends EditRecord
{
    protected static string $resource = CouponResource::class;

    public function getTitle(): string
    {
        return __('admin.edit_item', ['model' => static::getResource()::getModelLabel()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
