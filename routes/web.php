<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    // Petugas Dashboard
    Route::get('/petugas/dashboard', function () {
        $orders = \App\Models\Order::with('items')->latest()->get();
        $products = \App\Models\Product::latest()->get();
        $reviews = \App\Models\Review::with(['user', 'product'])->latest()->get();
        return view('petugas.dashboard', compact('orders', 'products', 'reviews'));
    })->middleware('role:petugas')->name('petugas.dashboard');

    Route::resource('petugas/products', ProductController::class)->names([
        'index' => 'petugas.products.index',
        'store' => 'petugas.products.store',
        'update' => 'petugas.products.update',
        'destroy' => 'petugas.products.destroy',
    ])->middleware('role:petugas');

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Customer Home
    Route::get('/customer/home', function () {
        return view('customer.home');
    })->middleware('role:customer')->name('customer.home');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');

    // Order Routes
    Route::get('/customer/orders', function () {
        $orders = \App\Models\Order::where('user_id', auth()->id())->with('items')->latest()->get();
        return view('customer.orders', compact('orders'));
    })->name('customer.orders');

    Route::get('/orders/sync/statuses', [App\Http\Controllers\Api\OrderSyncController::class, 'getActiveStatuses'])->name('api.orders.statuses');

    // Favorite Routes
    Route::post('/favorite/toggle', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorite.toggle');
    
    // Review Routes
    Route::post('/review/store', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
