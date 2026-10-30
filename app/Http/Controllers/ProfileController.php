<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// หน้า 09 — บัญชีของฉัน (ข้อมูลส่วนตัว / ที่อยู่ / ประวัติคำสั่งซื้อ)
class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $orders = $user->orders()->withCount('products')->latest()->get();

        return view('profile', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($data);

        return redirect()->route('profile')->with('success', 'บันทึกข้อมูลส่วนตัวแล้ว');
    }

    public function updateAddress(Request $request)
    {
        $data = $request->validate([
            'address' => ['required', 'string'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
        ]);

        $request->user()->update($data);

        return redirect()->to(route('profile') . '#address')->with('success', 'บันทึกที่อยู่จัดส่งแล้ว');
    }

    // รายละเอียดคำสั่งซื้อ (ลิงก์ "ดูรายละเอียดคำสั่งซื้อ →")
    public function order(Request $request, Order $order)
    {
        // ดูได้เฉพาะคำสั่งซื้อของตัวเอง
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        $order->load('products');

        return view('orders.show', compact('order'));
    }
}
