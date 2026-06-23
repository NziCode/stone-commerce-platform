<?php

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\PostController;
use App\Http\Controllers\Front\EventController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\NewsletterController;
use App\Http\Controllers\Front\WishlistController;
use App\Http\Controllers\Front\ReviewController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\SearchController;
use App\Services\LanguageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// ── Sitemap ──────────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', function () {
    if (!file_exists(public_path('sitemap.xml'))) {
        Artisan::call('sitemap:generate');
    }
    return response()->file(public_path('sitemap.xml'), ['Content-Type' => 'application/xml']);
})->name('sitemap');

// ── Robots.txt ───────────────────────────────────────────────────────────────
Route::get('/robots.txt', function () {
    $content = \App\Models\Setting::get(
        'robots_txt',
        "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml')
    );
    return response($content)->header('Content-Type', 'text/plain');
})->name('robots');

// ── Frontend locale switcher ──────────────────────────────────────────────────
Route::get('/set-locale/{locale}', function (string $locale) {
    $validLocales = LanguageService::getLocales();
    $oldLocale    = app()->getLocale();

    if (in_array($locale, $validLocales)) {
        session(['locale' => $locale]);
    }

    $previousUrl = url()->previous();
    $baseUrl     = url('/');
    $path        = str_replace($baseUrl, '', $previousUrl);

    foreach ($validLocales as $l) {
        $path = preg_replace('#^/' . $l . '(/|$)#', '/', $path);
    }
    $path = '/' . ltrim($path, '/');

    // ── Translate the slug segment for known resource routes ──────────────
    $segments = array_values(array_filter(explode('/', $path)));

    $resourceMap = [
        'products'   => \App\Models\Product::class,
        'categories' => \App\Models\Category::class,
        'news'       => \App\Models\Post::class,
        'events'     => \App\Models\Event::class,
    ];

    if (count($segments) === 2 && isset($resourceMap[$segments[0]])) {
        [$resource, $oldSlug] = $segments;
        $modelClass = $resourceMap[$resource];

        $record = $modelClass::query()
            ->whereJsonContains("slug->{$oldLocale}", $oldSlug)
            ->first();

        if ($record) {
            $newSlug = $record->getTranslation('slug', $locale)
                ?: $record->getTranslation('slug', 'en')
                    ?: $oldSlug;

            $path = "/{$resource}/{$newSlug}";
        }
    }

    app()->setLocale($locale);

    return $locale === LanguageService::getDefault()?->code
        ? redirect($baseUrl . $path)
        : redirect($baseUrl . '/' . $locale . $path);
})->name('set.locale');

// ── Admin locale switcher ─────────────────────────────────────────────────────
Route::post('/admin/set-locale', function (Request $request) {
    $locale  = $request->input('locale');
    $allowed = LanguageService::getLocales();

    if (in_array($locale, $allowed)) {
        session(['admin_locale' => $locale]);

        if (auth()->check()) {
            auth()->user()->update(['preferred_admin_locale' => $locale]);
        }
    }

    return back();
})->middleware(['web', 'auth'])->name('admin.set-locale');

// ── Localized routes ──────────────────────────────────────────────────────────
Route::group([
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localeSessionRedirect', 'localeViewPath'],
], function () {

    // Auth
    require __DIR__ . '/auth.php';

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
    });

    // Categories — show reuses the products index view with $category injected
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
    });

    // News / Posts
    Route::prefix('news')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/{slug}', [PostController::class, 'show'])->name('show');
    });

    // Exhibitions / Events
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/{slug}', [EventController::class, 'show'])->name('show');
    });

    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // Newsletter
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::get('/newsletter/subscribed', [NewsletterController::class, 'subscribed'])->name('newsletter.subscribed');
    Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::post('/coupon', [CartController::class, 'applyCoupon'])->name('coupon');
    });

    // Authenticated routes
    Route::middleware(['auth'])->group(function () {

        // Checkout
        Route::prefix('checkout')->name('checkout.')->group(function () {
            Route::get('/', [CheckoutController::class, 'index'])->name('index');
            Route::post('/', [CheckoutController::class, 'store'])->name('store');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        });

        // Payments
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/callback/{gateway}', [PaymentController::class, 'callback'])->name('callback');
            Route::get('/{order}', [PaymentController::class, 'index'])->name('index');
            Route::post('/{order}/online', [PaymentController::class, 'payOnline'])->name('online');
            Route::post('/{order}/receipt', [PaymentController::class, 'uploadReceipt'])->name('receipt');
        });

        // Wishlist
        Route::prefix('wishlist')->name('wishlist.')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('index');
            Route::post('/toggle/{product}', [WishlistController::class, 'toggle'])->name('toggle');
        });

        // Reviews
        Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        });
    });

    // CMS pages — must be last to avoid catching other routes
    Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');
});
