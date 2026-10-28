@extends('layouts.app')

@section('content')
<main class="cream-page">
    <span class="orange-kicker">CHECKOUT</span>
    <h1>สรุปคำสั่งซื้อ</h1>
    <p class="lead">กรอกข้อมูลจัดส่งและเลือกวิธีชำระเงินให้ครบถ้วน</p>

    <form action="{{ route('checkout.process') }}" method="POST" class="checkout-layout">
        @csrf
        <div>
            <section class="form-card">
                <h2>1. ที่อยู่จัดส่ง</h2>
                <div class="form-grid">
                    <label><span>ชื่อผู้รับ</span><input name="name" value="{{ Auth::user()->name }}" required></label>
                    <label><span>เบอร์โทรศัพท์</span><input name="phone" value="{{ Auth::user()->phone }}" required></label>
                    <label class="wide"><span>ที่อยู่</span><input name="address" value="{{ Auth::user()->address }}" required></label>
                </div>
            </section>

            <section class="form-card payment">
                <h2>2. วิธีชำระเงิน</h2>
                <label><input type="radio" name="payment_method" value="transfer" checked> โอนเงิน / Mobile Banking</label>
                <label><input type="radio" name="payment_method" value="cod"> เก็บเงินปลายทาง</label>
            </section>
        </div>

        <aside class="order-card">
            <h2>รายการสั่งซื้อ</h2>
            @php $subtotal = $cartItems->sum(fn($i) => $i->product->price * $i->quantity); @endphp
            @foreach($cartItems as $item)
                <p>
                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <b>฿{{ number_format($item->product->price * $item->quantity) }}</b>
                </p>
            @endforeach
            <hr />
            <p class="order-total">
                <b>ยอดชำระทั้งหมด</b>
                <strong>฿{{ number_format($subtotal + 40) }}</strong>
            </p>
            <button type="submit">ยืนยันคำสั่งซื้อ</button>
        </aside>
    </form>
</main>
@endsection