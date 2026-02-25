<?php

namespace App\Http\Controllers;

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

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        $user = auth()->user();

        DB::beginTransaction();
        try {
            // No balance deduction - COD only

            // Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'address' => $user->address ?? 'No Address Provided',
                'phone_number' => $user->phone_number,
                'notes' => $request->notes,
            ]);

            // Create Order Items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                ]);
            }

            session()->forget('cart');
            DB::commit();

            return redirect()->route('customer.orders')->with('success', 'Order placed successfully! Please prepare the cash for COD.');
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
