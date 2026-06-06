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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


// ── Sitemap و Robots ────────────────────────────────────────
Route::get('/sitemap.xml', function () {
    if (!file_exists(public_path('sitemap.xml'))) {
        Artisan::call('sitemap:generate');
    }
    return response()->file(public_path('sitemap.xml'), [
        'Content-Type' => 'application/xml',
    ]);
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = \App\Models\Setting::get(
        'robots_txt',
        "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml')
    );
    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots');

// ── Localization Group ──────────────────────────────────────
Route::group([
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localeSessionRedirect', 'localeViewPath'],
], function () {

    // ── صفحه اصلی ──────────────────────────────────────────
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // ── جستجو ──────────────────────────────────────────────
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // ── محصولات ────────────────────────────────────────────
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
    });

    // ── دسته‌بندی‌ها ────────────────────────────────────────
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
    });

    // ── اخبار ──────────────────────────────────────────────
    Route::prefix('news')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/{slug}', [PostController::class, 'show'])->name('show');
    });

    // ── نمایشگاه‌ها ─────────────────────────────────────────
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/{slug}', [EventController::class, 'show'])->name('show');
    });

    // ── تماس با ما ─────────────────────────────────────────
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    // ── خبرنامه ────────────────────────────────────────────
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

    // ── سبد خرید ───────────────────────────────────────────
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::post('/coupon', [CartController::class, 'applyCoupon'])->name('coupon');
    });

    // ── Auth (Breeze) ───────────────────────────────────────
    require __DIR__ . '/auth.php';

    // ── نیاز به لاگین ──────────────────────────────────────
    Route::middleware(['auth'])->group(function () {

        // تسویه حساب
        Route::prefix('checkout')->name('checkout.')->group(function () {
            Route::get('/', [CheckoutController::class, 'index'])->name('index');
            Route::post('/', [CheckoutController::class, 'store'])->name('store');
        });

        // سفارشات
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        });

        // پرداخت
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/{order}', [PaymentController::class, 'index'])->name('index');
            Route::post('/{order}/online', [PaymentController::class, 'payOnline'])->name('online');
            Route::post('/{order}/receipt', [PaymentController::class, 'uploadReceipt'])->name('receipt');
            Route::get('/callback/{gateway}', [PaymentController::class, 'callback'])->name('callback');
        });

        // علاقه‌مندی‌ها
        Route::prefix('wishlist')->name('wishlist.')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('index');
            Route::post('/toggle/{product}', [WishlistController::class, 'toggle'])->name('toggle');
        });

        // نظرات
        Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');

        // پروفایل
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        });
    });

    // ── صفحات CMS — باید آخر باشه ──────────────────────────
    Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');
});
