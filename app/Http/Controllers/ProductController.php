<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // หน้า 02 — ค้นหา / รายการสินค้า
    public function index(Request $request)
    {
        $query = Product::withStats();

        // ค้นหาจากชื่อ แบรนด์ หรือคำอธิบายสั้น
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        // กรองตามประเภทสัตว์: dog, cat, small_pet, fish
        if ($request->filled('pet') && array_key_exists($request->pet, Product::PET_TYPES)) {
            $query->where('pet_type', $request->pet);
        }

        $products = $query->orderBy('id')->get();

        return view('products.index', compact('products'));
    }

    // หน้า 04 — รายละเอียดสินค้า
    public function show(Product $product)
    {
        $product = Product::withStats()->findOrFail($product->id);

        $reviews = $product->reviews()->with('user')->latest()->get();

        // รีวิวของผู้ใช้คนนี้ (ถ้ามี) ใช้เติมค่าในฟอร์มรีวิว
        $myReview = auth()->check()
            ? $reviews->firstWhere('user_id', auth()->id())
            : null;

        return view('products.show', compact('product', 'reviews', 'myReview'));
    }
}
