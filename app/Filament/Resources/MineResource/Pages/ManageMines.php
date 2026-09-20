<?php

namespace App\Filament\Resources\MineResource\Pages;

use App\Filament\Resources\MineResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMines extends ManageRecords
{
    protected static string $resource = MineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('افزودن معدن'),
        ];
    }
}
