<?php

namespace App\Translation;

use Illuminate\Translation\FileLoader;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null): array
    {
        // اگر جدول هنوز ساخته نشده (مثلاً قبل از migrate)
        if (!Schema::hasTable('translations')) {
            return parent::load($locale, $group, $namespace);
        }

        // namespace خاص (مثل Filament) → از فایل بخوان
        if ($namespace && $namespace !== '*') {
            return parent::load($locale, $group, $namespace);
        }

        // از دیتابیس بخوان (با کش)
        $db = Translation::getCached($locale, $group);

        // اگر دیتابیس خالی بود، از فایل بخوان (fallback)
        if (empty($db)) {
            return parent::load($locale, $group, $namespace);
        }

        return $db;
    }
}
