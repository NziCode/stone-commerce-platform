<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Services\LanguageService;

class AdminLanguageSwitcher extends Widget
{
    protected static string $view    = 'filament.widgets.admin-language-switcher';
    protected static bool   $isLazy  = false;
    protected static ?int   $sort    = -10;
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'languages'     => LanguageService::getActive(),
            'currentLocale' => app()->getLocale(),
        ];
    }
}
