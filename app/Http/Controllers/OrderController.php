<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,delivering,completed',
        ]);

        // Block status advancement if payment is not verified
        if ($request->status === 'preparing' && $order->payment_status !== 'verified') {
            return back()->with('error', 'Tidak dapat memproses pesanan. Pembayaran belum diverifikasi.');
        }

        $data = ['status' => $request->status];
        
        // If handled by an officer/admin, record the petugas_id if not already set
        if (!$order->petugas_id && (auth()->user()->hasRole('petugas') || auth()->user()->hasRole('admin'))) {
            $data['petugas_id'] = auth()->id();
        }

        if ($request->status === 'preparing') $data['preparing_at'] = now();
        if ($request->status === 'delivering') $data['delivering_at'] = now();
        if ($request->status === 'completed') $data['completed_at'] = now();

        $order->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Mengubah Status Pesanan',
            'details' => "Mengubah status Order #ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . " menjadi {$request->status}",
        ]);

        return back()->with('success', 'Order status updated to ' . $request->status);
    }

    /**
     * Show QRIS payment page for a specific order.
     */
    public function showPayment(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check for payment timeout
        if ($order->isExpired()) {
            $order->markAsCancelled();
            return redirect()->route('customer.orders')->with('error', 'Pesanan Anda telah dibatalkan karena batas waktu pembayaran (10 menit) telah habis.');
        }

        return view('customer.payment', compact('order'));
    }

    /**
     * Handle payment receipt upload from customer.
     */
    public function uploadReceipt(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check for payment timeout before accepting upload
        if ($order->isExpired()) {
            $order->markAsCancelled();
            return redirect()->route('customer.orders')->with('error', 'Maaf, batas waktu pembayaran telah habis dan pesanan telah dibatalkan.');
        }

        $request->validate([
            'payment_receipt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Store the uploaded file
        $path = $request->file('payment_receipt')->store('payments', 'public');

        $order->update([
            'payment_receipt' => $path,
            'payment_status' => 'verifying',
        ]);

        return redirect()->route('customer.orders')->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi.');
    }

    /**
     * Verify/approve payment (Petugas/Admin).
     */
    public function verifyPayment(Order $order)
    {
        $order->update([
            'payment_status' => 'verified',
            'petugas_id' => auth()->id() // Record who verified the payment
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Memverifikasi Pembayaran',
            'details' => "Memverifikasi pembayaran untuk Order #ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT),
        ]);
        return back()->with('success', 'Pembayaran untuk Order #ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . ' telah diverifikasi.');
    }

    /**
     * Reject payment (Petugas/Admin).
     */
    public function rejectPayment(Order $order)
    {
        $order->update(['payment_status' => 'invalid']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Menolak Pembayaran',
            'details' => "Menolak pembayaran untuk Order #ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT),
        ]);
        return back()->with('success', 'Pembayaran untuk Order #ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . ' ditolak. Customer dapat mengunggah ulang.');
    }

    /**
     * Cancel an order (Customer).
     */
    public function cancel(Order $order)
    {
        // Only allow cancel if it's the customer's order and it's still awaiting payment
        if ($order->user_id !== auth()->id() || $order->payment_status !== 'awaiting_payment') {
            abort(403, 'Pesanan tidak dapat dibatalkan.');
        }

        // Use refactored method in model
        $order->markAsCancelled();

        return back()->with('success', 'Pesanan berhasil dibatalkan dan stok telah dikembalikan.');
    }
}
