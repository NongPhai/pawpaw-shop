<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index');
        }

        return view('checkout.index', compact('cartItems'));
    }

    public function process(Request $request)
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index');
        }

        $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $total = $subtotal + 40; // ค่าจัดส่ง 40 บาท

        DB::transaction(function () use ($cartItems, $total) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                ]);
            }

            // ล้างสินค้าในตะกร้าหลังจากสร้างคำสั่งซื้อสำเร็จ
            CartItem::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('profile');
    }
}