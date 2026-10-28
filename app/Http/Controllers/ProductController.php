<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // กรองตามคำค้นหา
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // กรองตามประเภทสัตว์เลี้ยง (dog, cat, rabbit, other)
        if ($request->has('pet') && $request->pet != '') {
            $query->where('pet_type', $request->pet);
        }

        $products = $query->latest()->get();

        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('products.show', compact('product'));
    }
}