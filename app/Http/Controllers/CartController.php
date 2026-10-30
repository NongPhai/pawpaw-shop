<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * หน้า 05 — ตะกร้าสินค้า
 * ตาราง cart_items เป็นตารางกลาง M:N ระหว่าง users กับ products (PK = user_id + product_id)
 * จึงใช้ relationship $user->cartProducts() กับ attach / updateExistingPivot / detach
 */
class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartProducts()->with('category')->get();
        $subtotal = $cartItems->sum(fn ($p) => $p->price * $p->pivot->quantity);
        $shippingFee = $cartItems->isEmpty() ? 0 : Order::SHIPPING_FEE;

        return view('cart.index', compact('cartItems', 'subtotal', 'shippingFee'));
    }

    // ปุ่ม "ใส่ตะกร้า" และ "ซื้อเลย" ในหน้ารายละเอียดสินค้า
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $user = $request->user();
        $product = Product::findOrFail($data['product_id']);
        $current = $user->cartProducts()->where('product_id', $product->id)->first()?->pivot->quantity ?? 0;
        $newQuantity = $current + $data['quantity'];

        if ($newQuantity > $product->stock) {
            return back()->with('error', "สินค้ามีในสต็อกเพียง {$product->stock} ชิ้น");
        }

        if ($current > 0) {
            $user->cartProducts()->updateExistingPivot($product->id, ['quantity' => $newQuantity]);
        } else {
            $user->cartProducts()->attach($product->id, ['quantity' => $newQuantity]);
        }

        // กด "ซื้อเลย" ไปหน้า checkout ทันที
        if ($request->input('action') === 'buy_now') {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'เพิ่มสินค้าลงตะกร้าแล้ว');
    }

    // ปุ่ม − / + ในตะกร้า
    public function update(Request $request, Product $product)
    {
        $user = $request->user();
        $item = $user->cartProducts()->where('product_id', $product->id)->firstOrFail();
        $quantity = $item->pivot->quantity + ($request->input('change') === 'decrease' ? -1 : 1);

        if ($quantity < 1) {
            $user->cartProducts()->detach($product->id);
        } elseif ($quantity > $product->stock) {
            return back()->with('error', "สินค้ามีในสต็อกเพียง {$product->stock} ชิ้น");
        } else {
            $user->cartProducts()->updateExistingPivot($product->id, ['quantity' => $quantity]);
        }

        return redirect()->route('cart.index');
    }

    // ปุ่ม "นำออก"
    public function destroy(Request $request, Product $product)
    {
        $request->user()->cartProducts()->detach($product->id);

        return redirect()->route('cart.index');
    }
}
