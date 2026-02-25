<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderSyncController extends Controller
{
    public function getActiveStatuses()
    {
        $userId = auth()->id();
        
        $orders = Order::where('user_id', $userId)
            ->whereIn('status', ['pending', 'preparing', 'delivering', 'completed'])
            ->latest()
            ->get(['id', 'status', 'preparing_at', 'delivering_at', 'completed_at']);

        return response()->json([
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'preparing_time' => $order->preparing_at ? $order->preparing_at->format('H:i') : null,
                    'delivering_time' => $order->delivering_at ? $order->delivering_at->format('H:i') : null,
                    'completed_time' => $order->completed_at ? $order->completed_at->format('H:i') : null,
                ];
            })
        ]);
    }
}
