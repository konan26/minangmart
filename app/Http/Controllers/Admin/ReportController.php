<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Financial Metrics (Overall)
        $completedOrders = Order::where('status', 'completed');
        $totalRevenue = $completedOrders->sum('total_price');
        $totalShipping = $completedOrders->sum('shipping_cost');
        $netProductRevenue = $totalRevenue - $totalShipping;
        $orderCount = Order::count();
        $completedCount = $completedOrders->count();
        
        // 2. Best Selling Products (Top 5)
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(price * quantity) as total_sales'))
            ->groupBy('product_name')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();
            
        // 3. Courier Usage Distribution
        $courierStats = Order::whereNotNull('courier_type')
            ->select('courier_type', DB::raw('count(*) as count'))
            ->groupBy('courier_type')
            ->get();
            
        // 5. Status Distribution (Moving up)
        $statusStats = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // 6. Petugas Performance (New)
        $petugasStats = Order::where('status', 'completed')
            ->whereNotNull('petugas_id')
            ->join('users', 'orders.petugas_id', '=', 'users.id')
            ->select(
                'users.name',
                DB::raw('count(*) as order_count'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalShipping',
            'netProductRevenue',
            'orderCount',
            'completedCount',
            'topProducts',
            'courierStats',
            'statusStats',
            'petugasStats'
        ));
    }
}
