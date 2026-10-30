<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * หน้า 03 — รายการโปรด (ตาราง wishlists)
 * wishlists เป็นตารางกลาง M:N ระหว่าง users กับ products
 */
class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $products = $request->user()->wishlist()
            ->withAvg('reviews', 'rating')
            ->with('category')
            ->orderByPivot('created_at', 'desc')
            ->get();

        return view('favorites.index', compact('products'));
    }

    // ปุ่มหัวใจ: กดครั้งแรก = เพิ่ม, กดอีกครั้ง = เอาออก
    public function toggle(Request $request, Product $product)
    {
        $result = $request->user()->wishlist()->toggle($product->id);
        $favorited = count($result['attached']) > 0;

        if ($request->expectsJson()) {
            return response()->json(['favorited' => $favorited]);
        }

        return back();
    }
}
