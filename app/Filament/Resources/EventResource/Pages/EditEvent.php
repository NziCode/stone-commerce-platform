<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    public function getTitle(): string
    {
        return __('admin.edit_item', ['model' => static::getResource()::getModelLabel()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewOnSite')
                ->label('مشاهده در سایت')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn () => route('events.show', $this->getRecord()->getTranslation('slug', 'fa')))
                ->openUrlInNewTab()
                ->visible(fn () => $this->getRecord()->is_published),
            Actions\DeleteAction::make(),
        ];
    }

    /** Photos may have been uploaded in the form — refresh the captions table below it. */
    protected function afterSave(): void
    {
        $this->dispatch('eventGalleryChanged');
    }
}
