<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Pagination for global order history
        $allOrders = Order::with(['user', 'items'])->latest()->paginate(10);
        
        // Stats
        $totalOrdersCount = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalCustomers = User::role('customer')->count();

        return view('admin.dashboard', compact('allOrders', 'totalOrdersCount', 'totalRevenue', 'totalCustomers'));
    }
}
