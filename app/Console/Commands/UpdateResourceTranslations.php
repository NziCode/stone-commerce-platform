<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateResourceTranslations extends Command
{
    protected $signature   = 'filament:translate-resources';
    protected $description = 'Add translation methods to all Filament Resources';

    // تعریف mapping هر Resource
    private array $resources = [
        'CategoryResource'       => ['key' => 'categories',       'group' => 'products'],
        'ContactMessageResource' => ['key' => 'contact_messages', 'group' => 'management'],
        'CouponResource'         => ['key' => 'coupons',          'group' => 'store'],
        'EventResource'          => ['key' => 'events',           'group' => 'content'],
        'LanguageResource'       => ['key' => 'languages',        'group' => 'settings'],
        'MenuItemResource'       => ['key' => 'menus',            'group' => 'appearance'],
        'MenuResource'           => ['key' => 'menus',            'group' => 'appearance'],
        'NewsletterResource'     => ['key' => 'newsletters',      'group' => 'management'],
        'OrderResource'          => ['key' => 'orders',           'group' => 'orders'],
        'PageResource'           => ['key' => 'pages',            'group' => 'content'],
        'PaymentResource'        => ['key' => 'payments',         'group' => 'orders'],
        'PostResource'           => ['key' => 'posts',            'group' => 'content'],
        'ProductResource'        => ['key' => 'products',         'group' => 'products'],
        'RedirectResource'       => ['key' => 'redirects',        'group' => 'settings'],
        'ReviewResource'         => ['key' => 'reviews',          'group' => 'store'],
        'SettingResource'        => ['key' => 'settings',         'group' => 'settings'],
        'SliderResource'         => ['key' => 'sliders',          'group' => 'appearance'],
        'TranslationResource'    => ['key' => 'translations',     'group' => 'settings'],
        'UserResource'           => ['key' => 'users',            'group' => 'users'],
    ];

    public function handle(): int
    {
        $path = app_path('Filament/Resources');

        foreach ($this->resources as $resource => $config) {
            $file = "{$path}/{$resource}.php";

            if (!File::exists($file)) {
                $this->warn("Skipped (not found): {$resource}.php");
                continue;
            }

            $content = File::get($file);

            // اگر قبلاً اضافه شده، skip
            if (str_contains($content, 'getNavigationLabel')) {
                $this->info("Already updated: {$resource}");
                continue;
            }

            $methods = $this->buildMethods($config['key'], $config['group']);

            // بعد از اولین { در class بذار
            $content = preg_replace(
                '/^(class\s+' . $resource . '[^{]*\{)/m',
                "$1\n{$methods}",
                $content
            );

            File::put($file, $content);
            $this->info("Updated: {$resource}");
        }

        $this->info('All resources updated successfully.');
        return self::SUCCESS;
    }

    private function buildMethods(string $key, string $group): string
    {
        return <<<PHP

    public static function getNavigationLabel(): string
    {
        return __('admin.{$key}');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.{$group}');
    }

    public static function getModelLabel(): string
    {
        return __('admin.{$key}');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.{$key}');
    }

PHP;
    }
}
