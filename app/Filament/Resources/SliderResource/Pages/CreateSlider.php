<?php

namespace App\Filament\Resources\SliderResource\Pages;

use App\Filament\Resources\SliderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSlider extends CreateRecord
{
    protected static string $resource = SliderResource::class;

    public function getTitle(): string
    {
        return __('admin.create_new_item', ['model' => static::getResource()::getModelLabel()]);
    }
}
