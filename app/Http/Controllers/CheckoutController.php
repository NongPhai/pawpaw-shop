<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

// หน้า 06 — สรุปคำสั่งซื้อ (Checkout)
class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartProducts()->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index');
        }

        $subtotal = $cartItems->sum(fn ($p) => $p->price * $p->pivot->quantity);
        $shippingFee = Order::SHIPPING_FEE;

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'user' => $request->user(),
        ]);
    }

    public function process(Request $request)
    {
        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'payment_method' => ['required', Rule::in(array_keys(Order::PAYMENT_METHODS))],
        ]);

        $user = $request->user();

        // ทำทุกขั้นใน transaction: ถ้าขั้นไหนพัง จะไม่มีข้อมูลครึ่งๆ กลางๆ ค้างในฐานข้อมูล
        $order = DB::transaction(function () use ($user, $data) {
            $cartItems = $user->cartProducts()->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'ตะกร้าว่างอยู่']);
            }

            // ล็อกแถวสินค้าไว้ระหว่างตัดสต็อก กันคนสั่งพร้อมกันแล้วสต็อกติดลบ
            $products = Product::whereIn('id', $cartItems->pluck('id'))->lockForUpdate()->get()->keyBy('id');

            foreach ($cartItems as $item) {
                if ($products[$item->id]->stock < $item->pivot->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "{$item->name} มีในสต็อกเพียง {$products[$item->id]->stock} ชิ้น",
                    ]);
                }
            }

            $subtotal = $cartItems->sum(fn ($p) => $p->price * $p->pivot->quantity);

            $order = Order::create([
                ...$data,
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'shipping_fee' => Order::SHIPPING_FEE,
                'total' => $subtotal + Order::SHIPPING_FEE,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                // order_items เก็บราคา ณ วันที่สั่ง (unit_price) เผื่อราคาสินค้าเปลี่ยนทีหลัง
                $order->products()->attach($item->id, [
                    'quantity' => $item->pivot->quantity,
                    'unit_price' => $item->price,
                ]);
                $products[$item->id]->decrement('stock', $item->pivot->quantity);
            }

            // ลูกค้ายังไม่มีที่อยู่ในโปรไฟล์ → เก็บที่อยู่นี้ไว้ใช้ครั้งหน้า
            if (! $user->address) {
                $user->update([
                    'address' => $data['shipping_address'],
                    'province' => $data['province'],
                    'postal_code' => $data['postal_code'],
                ]);
            }

            $user->cartProducts()->detach();

            return $order;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'สั่งซื้อสำเร็จ! หมายเลขคำสั่งซื้อ ' . $order->number());
    }
}
