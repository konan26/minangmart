<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('customer.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->id;

        // Check stock availability
        $product = Product::find($id);
        if (!$product || $product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, stok produk ini sudah habis.'
            ], 400);
        }

        $currentQty = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        if ($currentQty + 1 > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, stok tersisa hanya ' . $product->stock . ' porsi.'
            ], 400);
        }
        
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "product_id" => $request->id,
                "name" => $request->name,
                "quantity" => 1,
                "price" => $request->price,
                "image" => $request->image
            ];
        }

        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'cart_count' => count($cart)
        ]);
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true, 'cart_count' => count($cart)]);
        }
    }

    public function updateQuantity(Request $request)
    {
        if ($request->id && $request->action) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                $product = Product::find($cart[$request->id]['product_id']);
                
                if ($request->action == 'increase') {
                    if ($product && $product->stock > $cart[$request->id]['quantity']) {
                        $cart[$request->id]['quantity']++;
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok tidak mencukupi.'
                        ], 400);
                    }
                } elseif ($request->action == 'decrease') {
                    if ($cart[$request->id]['quantity'] > 1) {
                        $cart[$request->id]['quantity']--;
                    }
                }
                
                session()->put('cart', $cart);
                
                // Calculate new totals
                $itemTotal = $cart[$request->id]['quantity'] * $cart[$request->id]['price'];
                $cartTotal = collect($cart)->sum(function($item) {
                    return $item['price'] * $item['quantity'];
                });

                return response()->json([
                    'success' => true,
                    'quantity' => $cart[$request->id]['quantity'],
                    'itemTotal' => $itemTotal,
                    'cartTotal' => $cartTotal
                ]);
            }
        }
        return response()->json(['success' => false], 400);
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Validate address before checkout
        if (empty(auth()->user()->address)) {
            return back()->with('error', 'Mohon lengkapi alamat Anda di profil sebelum melakukan checkout.');
        }

        // Validate stock before checkout
        foreach ($cart as $id => $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                $available = $product ? $product->stock : 0;
                return back()->with('error', "Stok {$item['name']} tidak cukup. Tersisa {$available} porsi.");
            }
        }

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $shippingCost = $request->input('shipping_cost', 0);
        $totalPrice += $shippingCost;

        $user = auth()->user();

        $customPhotoPath = null;
        if ($request->hasFile('custom_photo')) {
            $customPhotoPath = $request->file('custom_photo')->store('order_photos', 'public');
        }

        DB::beginTransaction();
        try {
            // Create Order with QRIS payment
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'address' => $user->address ?? 'No Address Provided',
                'phone_number' => $user->phone_number,
                'notes' => $request->notes,
                'payment_status' => 'awaiting_payment',
                'courier_type' => $request->input('courier_type', 'standard'),
                'shipping_cost' => $shippingCost,
                'custom_photo' => $customPhotoPath,
            ]);

            // Create Order Items & deduct stock
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                ]);

                // Deduct stock
                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            session()->forget('cart');
            DB::commit();

            return redirect()->route('orders.payment', $order->id)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran via QRIS.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function getCount()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    }
}
