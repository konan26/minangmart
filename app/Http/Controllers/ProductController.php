<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('petugas.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Menambahkan Produk',
            'details' => "Menambahkan produk baru: {$request->name} (Harga: {$request->price}, Stok: {$request->stock})",
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'stock' => $request->stock,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Mengubah Produk',
            'details' => "Mengubah produk: {$product->name} (Harga: {$request->price}, Stok: {$request->stock})",
        ]);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $productName = $product->name;
        $product->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Menghapus Produk',
            'details' => "Menghapus produk: {$productName}",
        ]);

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}
