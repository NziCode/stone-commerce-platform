<?php

namespace App\Filament\Resources\AttributeResource\Pages;

use App\Filament\Resources\AttributeResource;
use App\Models\Attribute;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAttribute extends EditRecord
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()->label(__('admin.delete'))];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('admin.updated_successfully'));
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['type'] ?? null) === 'bool') {
            $data['default_value'] = !empty($data['default_value_bool']) ? '1' : '0';
        }

        unset($data['default_value_bool']);
        unset($data['group_key']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->reorderSiblings($this->record);
    }

    protected function reorderSiblings(Attribute $attribute): void
    {
        $targetOrder = (int) $attribute->sort_order;

        $siblings = Attribute::where('id', '!=', $attribute->id)
            ->orderBy('sort_order')
            ->get();

        $order = 1;
        foreach ($siblings as $sibling) {
            if ($order === $targetOrder) {
                $order++;
            }
            $sibling->update(['sort_order' => $order]);
            $order++;
        }
    }
}
