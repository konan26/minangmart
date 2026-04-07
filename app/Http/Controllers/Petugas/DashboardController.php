<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // Load active orders first, then latest. Paginate to prevent memory limits
        $orders = Order::with('items', 'user')
            ->orderByRaw("FIELD(status, 'pending', 'preparing', 'delivering', 'completed', 'cancelled')")
            ->latest()
            ->paginate(15, ['*'], 'orders_page');
            
        $products = Product::latest()->get(); // Keep get() as product list is usually not massive and needed for modals
        $reviews = Review::with(['user', 'product'])->latest()->paginate(10, ['*'], 'reviews_page');
        
        return view('petugas.dashboard', compact('orders', 'products', 'reviews'));
    }
}
