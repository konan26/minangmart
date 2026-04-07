<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        if ($request->ajax()) {
            $product = \App\Models\Product::withCount('reviews')->withAvg('reviews', 'rating')->find($request->product_id);
            return response()->json([
                'success' => true,
                'message' => 'Rating saved!',
                'avg_rating' => number_format($product->reviews_avg_rating ?? 0, 1),
                'review_count' => $product->reviews_count
            ]);
        }

        return back()->with('success', 'Review submitted successfully!');
    }
}
