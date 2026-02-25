<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,delivering,completed',
        ]);

        $data = ['status' => $request->status];
        
        if ($request->status === 'preparing') $data['preparing_at'] = now();
        if ($request->status === 'delivering') $data['delivering_at'] = now();
        if ($request->status === 'completed') $data['completed_at'] = now();

        $order->update($data);

        return back()->with('success', 'Order status updated to ' . $request->status);
    }
}
