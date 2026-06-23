<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\HandleRedirects::class,
            \App\Http\Middleware\TrackVisitor::class,
        ]);

        $middleware->alias([
            'active'                => \App\Http\Middleware\RedirectIfNotActive::class,
            'set.locale'            => \App\Http\Middleware\SetLocale::class,
            'localize'              => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeViewPath'        => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'localizationRedirect'  => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return response()->view('errors.404', [], 404);
        });
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            $code = $e->getStatusCode();
            $view = view()->exists("errors.{$code}") ? "errors.{$code}" : 'errors.500';
            return response()->view($view, ['exception' => $e], $code);
        });
        $exceptions->render(function (\Throwable $e, $request) {
            if (!$request->expectsJson() && app()->environment('production')) {
                return response()->view('errors.500', ['exception' => $e], 500);
            }
        });
    })->create();
