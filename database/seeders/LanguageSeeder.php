<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'name'        => 'Persian',
                'native_name' => 'فارسی',
                'code'        => 'fa',
                'locale'      => 'fa_IR',
                'direction'   => 'rtl',
                'flag'        => 'FA',
                'is_default'  => true,
                'is_active'   => true,
                'sort_order'  => 0,
            ],
            [
                'name'        => 'English',
                'native_name' => 'English',
                'code'        => 'en',
                'locale'      => 'en_US',
                'direction'   => 'ltr',
                'flag'        => 'EN',
                'is_default'  => false,
                'is_active'   => true,
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Hindi',
                'native_name' => 'हिन्दी',
                'code'        => 'hi',
                'locale'      => 'hi_IN',
                'direction'   => 'ltr',
                'flag'        => 'HI',
                'is_default'  => false,
                'is_active'   => true,
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Italian',
                'native_name' => 'Italiano',
                'code'        => 'it',
                'locale'      => 'it_IT',
                'direction'   => 'ltr',
                'flag'        => 'IT',
                'is_default'  => false,
                'is_active'   => true,
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Arabic',
                'native_name' => 'العربية',
                'code'        => 'ar',
                'locale'      => 'ar_SA',
                'direction'   => 'rtl',
                'flag'        => 'AR',
                'is_default'  => false,
                'is_active'   => true,
                'sort_order'  => 4,
            ],
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(
                ['code' => $language['code']],
                $language
            );
        }
    }
}
