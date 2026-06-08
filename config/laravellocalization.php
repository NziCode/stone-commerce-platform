<?php

return [
    'supportedLocales' => [
        'fa' => [
            'name'       => 'Persian',
            'script'     => 'Arab',
            'native'     => 'فارسی',
            'regional'   => 'fa_IR',
            'direction'  => 'rtl',
        ],
        'en' => [
            'name'       => 'English',
            'script'     => 'Latn',
            'native'     => 'English',
            'regional'   => 'en_GB',
            'direction'  => 'ltr',
        ],
        'hi' => [
            'name'       => 'Hindi',
            'script'     => 'Deva',
            'native'     => 'हिन्दी',
            'regional'   => 'hi_IN',
            'direction'  => 'ltr',
        ],
        'it' => [
            'name'       => 'Italian',
            'script'     => 'Latn',
            'native'     => 'Italiano',
            'regional'   => 'it_IT',
            'direction'  => 'ltr',
        ],
        'ar' => [
            'name'       => 'Arabic',
            'script'     => 'Arab',
            'native'     => 'العربية',
            'regional'   => 'ar_AE',
            'direction'  => 'rtl',
        ],
    ],

    'useAcceptLanguageHeader'    => false,
    'hideDefaultLocaleInURL'     => false,  // fa در URL نشون داده نمیشه
    'defaultLocale'              => 'fa',
    'localesOrder'               => [],
    'localesMapping'             => [],
    'utf8suffix'                 => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),
    'urlsMiddleware'             => ['web'],
    'useCountryFlag'             => false,
];
