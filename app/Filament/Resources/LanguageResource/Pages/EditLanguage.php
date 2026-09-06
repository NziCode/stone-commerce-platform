<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Services\LanguageService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

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

    protected function afterSave(): void
    {
        LanguageService::clearCache();
    }
}
