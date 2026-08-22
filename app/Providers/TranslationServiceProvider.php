<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;
use App\Translation\DatabaseLoader;

class TranslationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * Bound here instead of register() on purpose: some other provider
     * (Laravel's own translation bootstrapping) re-binds 'translator' with
     * the default FileLoader during its register() phase. boot() always
     * runs after every provider's register() phase has finished, so binding
     * here guarantees this is the last write and DatabaseLoader wins.
     */
    public function boot(): void
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new DatabaseLoader(
                $app['files'],
                $app['path.lang']
            );
        });

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);
            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
