<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReview extends CreateRecord
{
    protected static string $resource = ReviewResource::class;

    public function getTitle(): string
    {
        return __('admin.create_new_item', ['model' => static::getResource()::getModelLabel()]);
    }
}
