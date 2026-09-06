<?php

namespace App\Filament\Resources\AttributeResource\Pages;

use App\Filament\Resources\AttributeResource;
use App\Models\Attribute;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;

    public function getTitle(): string
    {
        return __('admin.create_new_item', ['model' => static::getResource()::getModelLabel()]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('admin.created_successfully'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['type'] ?? null) === 'bool') {
            $data['default_value'] = !empty($data['default_value_bool']) ? '1' : '0';
        }

        unset($data['default_value_bool']);
        unset($data['group_key']);

        return $data;
    }

    protected function afterCreate(): void
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
