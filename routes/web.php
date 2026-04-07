<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        } else {
            return redirect()->route('customer.home');
        }
    }
    
    $products = \App\Models\Product::withCount('favorites')->withAvg('reviews', 'rating')->latest()->take(8)->get();
    return view('welcome', compact('products'));
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // Admin Petugas Management
    Route::resource('admin/petugas', \App\Http\Controllers\Admin\PetugasController::class)->names([
        'index' => 'admin.petugas.index',
        'store' => 'admin.petugas.store',
        'destroy' => 'admin.petugas.destroy',
    ])->middleware('role:admin');

    // Admin Activity Logs
    Route::get('/admin/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.activity_logs.index');

    // Admin Reports
    Route::get('/admin/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.reports.index');

    // Petugas Dashboard
    Route::get('/petugas/dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])
        ->middleware('role:petugas')
        ->name('petugas.dashboard');

    Route::resource('petugas/products', ProductController::class)->names([
        'index' => 'petugas.products.index',
        'store' => 'petugas.products.store',
        'update' => 'petugas.products.update',
        'destroy' => 'petugas.products.destroy',
    ])->middleware('role:petugas');

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // QRIS Payment Routes
    Route::get('/orders/{order}/payment', [OrderController::class, 'showPayment'])->name('orders.payment');
    Route::post('/orders/{order}/upload-receipt', [OrderController::class, 'uploadReceipt'])->name('orders.uploadReceipt');
    Route::patch('/orders/{order}/verify-payment', [OrderController::class, 'verifyPayment'])->middleware('role:petugas|admin')->name('orders.verifyPayment');
    Route::patch('/orders/{order}/reject-payment', [OrderController::class, 'rejectPayment'])->middleware('role:petugas|admin')->name('orders.rejectPayment');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Customer Home
    Route::get('/customer/home', function () {
        return view('customer.home');
    })->middleware('role:customer')->name('customer.home');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update_quantity');
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
