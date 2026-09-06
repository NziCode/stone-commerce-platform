<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

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

    protected function afterCreate(): void
    {
        $this->reorderSiblings($this->record);

        Category::fixTree();
    }

    protected function reorderSiblings(Category $category): void
    {
        $targetOrder = (int) $category->sort_order;

        $siblings = Category::when(
            $category->parent_id,
            fn ($q) => $q->where('parent_id', $category->parent_id),
            fn ($q) => $q->whereNull('parent_id')
        )
            ->where('id', '!=', $category->id)
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
