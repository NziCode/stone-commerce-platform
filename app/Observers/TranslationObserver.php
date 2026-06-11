<?php

namespace App\Observers;

use App\Models\Translation;

class TranslationObserver
{
    public function saved(Translation $translation): void
    {
        Translation::clearCache($translation->locale, $translation->group);
    }

    public function deleted(Translation $translation): void
    {
        Translation::clearCache($translation->locale, $translation->group);
    }
}
