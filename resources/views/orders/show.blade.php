@extends('layouts.app')

{{-- รายละเอียดคำสั่งซื้อ: ข้อมูลจากตาราง orders + order_items (ราคา ณ วันที่สั่ง) --}}
@section('content')
<main class="cream-page">
    <span class="orange-kicker">ORDER DETAILS</span>
    <h1>คำสั่งซื้อ {{ $order->number() }}</h1>
    <p class="lead">
        สั่งเมื่อ {{ $order->created_at->format('d/m/Y H:i') }} ·
        <span class="order-status status-{{ $order->status }}">● {{ $order->statusLabel() }}</span>
    </p>

    <div class="checkout-layout">
        <div>
            <section class="form-card">
                <h2>ที่อยู่จัดส่ง</h2>
                <p class="address-now">
                    <b>{{ $order->recipient_name }}</b> · {{ $order->recipient_phone }}<br>
                    {{ $order->shipping_address }} {{ $order->province }} {{ $order->postal_code }}
                </p>
            </section>
            <section class="form-card">
                <h2>วิธีชำระเงิน</h2>
                <p class="address-now">{{ \App\Models\Order::PAYMENT_METHODS[$order->payment_method] ?? $order->payment_method }}</p>

                {{-- โอนเงินแต่ยังไม่ชำระ: แสดง QR ให้สแกน (พอเปลี่ยน status เป็น paid แล้ว QR จะหายไป) --}}
                @if($order->payment_method === 'transfer' && $order->status === 'pending')
                    <div class="transfer-qr">
                        <img src="/images/payment/qr_demo.png" alt="QR สำหรับโอนเงิน (ตัวอย่าง)">
                        <div>
                            <b>สแกนเพื่อโอนเงิน</b>
                            <p>ยอดที่ต้องโอน <strong>฿{{ number_format($order->total) }}</strong></p>
                            <small>ร้านจะตรวจยอดและเปลี่ยนสถานะเป็น "ชำระเงินแล้ว"</small>
                        </div>
                    </div>
                @endif
            </section>
        </div>

        <aside class="order-card">
            <h2>รายการสินค้า</h2>
            @foreach($order->products as $product)
                <p>
                    <span>{{ $product->name }} × {{ $product->pivot->quantity }}</span>
                    <b>฿{{ number_format($product->pivot->unit_price * $product->pivot->quantity) }}</b>
                </p>
            @endforeach
            <hr />
            <p><span>รวมสินค้า</span><b>฿{{ number_format($order->subtotal) }}</b></p>
            <p><span>ค่าจัดส่ง</span><b>฿{{ number_format($order->shipping_fee) }}</b></p>
            <hr />
            <p class="order-total">
                <b>ยอดชำระทั้งหมด</b>
                <strong>฿{{ number_format($order->total) }}</strong>
            </p>
            <a href="{{ route('profile') }}#orders" class="continue-shopping">← กลับไปบัญชีของฉัน</a>
        </aside>
    </div>
</main>
@endsection
