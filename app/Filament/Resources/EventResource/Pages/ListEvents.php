<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.create_new_item', ['model' => static::getResource()::getModelLabel()])),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('همه')
                ->badge(Event::query()->count()),

            'current' => Tab::make('در حال برگزاری / پیش‌رو')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['ongoing', 'upcoming']))
                ->badge(Event::query()->whereIn('status', ['ongoing', 'upcoming'])->count()),

            'held' => Tab::make('برگزار شده')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'finished'))
                ->badge(Event::query()->where('status', 'finished')->count()),

            'draft' => Tab::make('پیش‌نویس (مخفی)')
                ->icon('heroicon-o-eye-slash')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_published', false))
                ->badge(Event::query()->where('is_published', false)->count())
                ->badgeColor('warning'),
        ];
    }
}
