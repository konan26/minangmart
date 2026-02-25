<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $userId = auth()->id();
        $productId = $request->product_id;

        $favorite = Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $status = 'added';
        }

        $count = Favorite::where('product_id', $productId)->count();

        return response()->json([
            'status' => $status,
            'count' => $count
        ]);
    }
}
