<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    // หน้า 01 — หน้าแรก
    public function index()
    {
        // "ของโปรดที่น้อง ๆ เลิฟ": สินค้าที่มีป้าย (badge) ก่อน แล้วค่อยสินค้าอื่น เอา 4 ชิ้น
        $recommendedProducts = Product::withStats()
            ->orderByRaw('badge IS NULL')
            ->orderBy('id')
            ->take(4)
            ->get();

        // ราคาเริ่มต้นที่ถูกที่สุด สำหรับการ์ด "เริ่ม ฿..." ใน hero
        $startingPrice = Product::where('stock', '>', 0)->min('price');

        // จำนวนสินค้าในแต่ละประเภทสัตว์ สำหรับกล่องหมวดหมู่
        $petCounts = Product::selectRaw('pet_type, COUNT(*) as total')
            ->groupBy('pet_type')
            ->pluck('total', 'pet_type');

        return view('home', compact('recommendedProducts', 'startingPrice', 'petCounts'));
    }
}
