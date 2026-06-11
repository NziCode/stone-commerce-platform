<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        $this->reorderSiblings($this->record);
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
