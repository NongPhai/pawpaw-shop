@extends('layouts.app')

{{-- หน้า 05 — ตะกร้าสินค้า ($cartItems = สินค้า, จำนวนอยู่ใน $item->pivot->quantity) --}}
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
                        <span style="background: {{ $item->cardColor() }};">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="" style="width: 40px;">
                            @else
                                {{ $item->emoji() }}
                            @endif
                        </span>
                        <div>
                            <a href="{{ route('products.show', $item) }}"><b>{{ $item->name }}</b></a>
                            <small>{{ collect([$item->size ? 'ขนาด ' . $item->size : null, 'สำหรับ' . $item->petTypeLabel()])->filter()->join(' · ') }}</small>
                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-link">นำออก</button>
                            </form>
                        </div>
                    </div>

                    <div class="quantity cart-qty">
                        <form action="{{ route('cart.update', $item) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" name="change" value="decrease">−</button>
                        </form>
                        <span>{{ $item->pivot->quantity }}</span>
                        <form action="{{ route('cart.update', $item) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" name="change" value="increase">+</button>
                        </form>
                    </div>

                    <b>฿{{ number_format($item->price * $item->pivot->quantity) }}</b>
                </div>
            @empty
                <div class="empty-cart" style="text-align: center; padding: 40px;">
                    <b>ตะกร้าของคุณยังว่างอยู่</b>
                    <p><a href="{{ route('products.index') }}">ไปเลือกซื้อของให้น้องกัน →</a></p>
                </div>
            @endforelse
        </section>

        <div>
            <aside class="summary">
                <h2>สรุปยอดเบื้องต้น</h2>
                <p>
                    <span>รวมสินค้า ({{ $cartItems->sum('pivot.quantity') }} ชิ้น)</span>
                    <b>฿{{ number_format($subtotal) }}</b>
                </p>
                <p>
                    <span>ค่าจัดส่ง</span>
                    <b>฿{{ number_format($shippingFee) }}</b>
                </p>
                <hr />
                <p class="total">
                    <b>ยอดรวมทั้งหมด</b>
                    <strong>฿{{ number_format($subtotal + $shippingFee) }}</strong>
                </p>
                @if($cartItems->isNotEmpty())
                    <a href="{{ route('checkout.index') }}" class="auth-submit summary-button">ดำเนินการสั่งซื้อ →</a>
                    <small class="secure-note">🔒 ข้อมูลของคุณปลอดภัย</small>
                @endif
            </aside>
            <a href="{{ route('products.index') }}" class="continue-shopping">เลือกซื้อสินค้าต่อ</a>
        </div>
    </div>
</main>
@endsection
