@extends('layouts.app')

{{-- หน้า 06 — สรุปคำสั่งซื้อ: ข้อมูลที่กรอกจะถูกบันทึกลงตาราง orders --}}
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
                    <label><span>ชื่อผู้รับ</span>
                        <input name="recipient_name" placeholder="ชื่อ-นามสกุล" required
                               value="{{ old('recipient_name', $user->fullName()) }}"></label>
                    <label><span>เบอร์โทรศัพท์</span>
                        <input name="recipient_phone" placeholder="08X-XXX-XXXX" required
                               value="{{ old('recipient_phone', $user->phone) }}"></label>
                    <label class="wide"><span>ที่อยู่</span>
                        <input name="shipping_address" placeholder="บ้านเลขที่ / ถนน / แขวง / เขต" required
                               value="{{ old('shipping_address', $user->address) }}"></label>
                    <label><span>จังหวัด</span>
                        <input name="province" placeholder="เลือกจังหวัด" required
                               value="{{ old('province', $user->province) }}"></label>
                    <label><span>รหัสไปรษณีย์</span>
                        <input name="postal_code" placeholder="XXXXX" required maxlength="10"
                               value="{{ old('postal_code', $user->postal_code) }}"></label>
                </div>
            </section>

            <section class="form-card payment">
                <h2>2. วิธีชำระเงิน</h2>
                @foreach(\App\Models\Order::PAYMENT_METHODS as $value => $label)
                    <label>
                        <input type="radio" name="payment_method" value="{{ $value }}"
                               {{ old('payment_method', 'transfer') === $value ? 'checked' : '' }}>
                        {{ $label }}
                    </label>
                @endforeach

                {{-- เลือก "โอนเงิน" แล้วแสดง QR (QR ตัวอย่าง ไม่ใช่บัญชีจริง) --}}
                <div class="transfer-qr" id="transfer-qr">
                    <img src="/images/payment/qr_demo.png" alt="QR สำหรับโอนเงิน (ตัวอย่าง)">
                    <div>
                        <b>สแกนเพื่อโอนเงิน</b>
                        <p>ยอดที่ต้องโอน <strong>฿{{ number_format($subtotal + $shippingFee) }}</strong></p>
                        <small>โอนแล้วกดยืนยันคำสั่งซื้อ ร้านจะตรวจยอดและเปลี่ยนสถานะเป็น "ชำระเงินแล้ว"</small>
                    </div>
                </div>
            </section>
        </div>

        <aside class="order-card">
            <h2>รายการสั่งซื้อ</h2>
            @foreach($cartItems as $item)
                <p>
                    <span>{{ $item->name }} × {{ $item->pivot->quantity }}</span>
                    <b>฿{{ number_format($item->price * $item->pivot->quantity) }}</b>
                </p>
            @endforeach
            <hr />
            <p><span>รวมสินค้า</span><b>฿{{ number_format($subtotal) }}</b></p>
            <p><span>ค่าจัดส่ง</span><b>฿{{ number_format($shippingFee) }}</b></p>
            <hr />
            <p class="order-total">
                <b>ยอดชำระทั้งหมด</b>
                <strong>฿{{ number_format($subtotal + $shippingFee) }}</strong>
            </p>
            <button type="submit">ยืนยันคำสั่งซื้อ</button>
            <small class="secure-note">เมื่อยืนยันคำสั่งซื้อ ถือว่ายอมรับเงื่อนไขการซื้อขาย</small>
        </aside>
    </form>
</main>

<script>
// แสดง QR เฉพาะตอนเลือก "โอนเงิน"
(function () {
    const qr = document.getElementById('transfer-qr');
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const update = () => {
        const checked = document.querySelector('input[name="payment_method"]:checked');
        qr.style.display = checked && checked.value === 'transfer' ? 'flex' : 'none';
    };
    radios.forEach(r => r.addEventListener('change', update));
    update();
})();
</script>
@endsection
