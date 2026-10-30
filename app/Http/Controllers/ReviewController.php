<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

// รีวิว + ให้ดาว (extra feature) ในหน้ารายละเอียดสินค้า
class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // 1 คนรีวิวสินค้าได้ 1 ครั้ง (unique user_id + product_id) ถ้ารีวิวซ้ำจะเป็นการแก้รีวิวเดิม
        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            $data,
        );

        return redirect()->route('products.show', $product)
            ->with('success', 'ขอบคุณสำหรับรีวิว!')
            ->withFragment('reviews');
    }
}
