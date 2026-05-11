<?php

declare(strict_types=1);

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// SEO: Sitemap
Route::get('/sitemap.xml', function () {
    $products = Product::latest()->get();

    return response()->view('seo.sitemap', compact('products'))
        ->header('Content-Type', 'text/xml');
})->name('sitemap');

// Public routes
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');

// Checkout & Payment (Public - No login required)
Route::get('/checkout/{product}', [PaymentController::class, 'checkout'])->name('checkout');
Route::post('/checkout/{product}', [PaymentController::class, 'initMoneroo'])
    ->middleware('throttle:10,1')
    ->name('checkout.init');

// Authenticated Routes (Login required)
Route::middleware('auth')->group(function () {
    // My Purchases (Client Dashboard)
    Route::get('/dashboard', function () {
        $orders = Order::where('user_id', auth()->id())
            ->where('status', 'success')
            ->with('product')
            ->latest()
            ->get();

        return view('dashboard', compact('orders'));
    })->name('dashboard');

    // Profile Management (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Moneroo return URL (redirected back from Moneroo)
Route::get('/payment/moneroo/return/{order}', [PaymentController::class, 'monerooReturn'])->name('payment.moneroo.return');

// Moneroo webhook (POST, signed)
Route::post('/payment/moneroo/webhook', [PaymentController::class, 'monerooWebhook'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('payment.moneroo.webhook');

// Payment success page (public fallback)
Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');

// Secure download via token
Route::get('/download/{token}', [DownloadController::class, 'downloadByToken'])
    ->middleware('throttle:30,1')
    ->name('download');

// Admin Routes (protect via middleware 'admin')
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
