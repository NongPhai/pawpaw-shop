@extends('layouts.app')

@section('content')
<main class="cream-page">
    <span class="orange-kicker">YOUR CART</span>
    <h1>ตะกร้าสินค้า</h1>
    <p class="lead">ตรวจสอบสินค้าและจำนวนก่อนดำเนินการสั่งซื้อ</p>

    <div class="cart-layout">
        <section class="cart-list">
            <div class="cart-head">
                <span>สินค้า</span>
                <span>จำนวน</span>
                <span>ราคา</span>
            </div>

            @forelse($cartItems as $item)
                <div class="cart-row">
                    <div class="cart-product">
                        <span style="background: #eee8ff;">
                            <img src="{{ $item->product->image }}" style="width: 40px;">
                        </span>
                        <div>
                            <b>{{ $item->product->name }}</b>
                            <small>สำหรับสัตว์เลี้ยง</small>
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: red; background: none; border: none; cursor: pointer;">นำออก</button>
                            </form>
                        </div>
                    </div>
                    <div>{{ $item->quantity }}</div>
                    <b>฿{{ number_format($item->product->price * $item->quantity) }}</b>
                </div>
            @empty
                <div class="empty-cart" style="text-align: center; padding: 40px;">
                    <b>ตะกร้าของคุณยังว่างอยู่</b>
                </div>
            @endforelse
        </section>

        <aside class="summary">
            <h2>สรุปยอดเบื้องต้น</h2>
            @php $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity); @endphp
            <p>
                <span>รวมสินค้า ({{ $cartItems->sum('quantity') }} ชิ้น)</span>
                <b>฿{{ number_format($subtotal) }}</b>
            </p>
            <p>
                <span>ค่าจัดส่ง</span>
                <b>฿{{ $subtotal > 0 ? 40 : 0 }}</b>
            </p>
            <hr />
            <p class="total">
                <b>ยอดรวมทั้งหมด</b>
                <strong>฿{{ number_format($subtotal + ($subtotal > 0 ? 40 : 0)) }}</strong>
            </p>
            <a href="{{ route('checkout.index') }}" class="auth-submit" style="display: block; text-align: center; text-decoration: none; margin-top: 15px;">
                ดำเนินการสั่งซื้อ →
            </a>
        </aside>
    </div>
</main>
@endsection