@extends('layouts.app')

@section('content')
<main class="cream-page product-page">
    <span class="orange-kicker">PRODUCT DETAILS</span>
    <h1>{{ $product->name }}</h1>
    <p class="lead">โภชนาการที่สมดุล เพื่อสุขภาพดีและพลังงานในทุกวัน</p>

    <div class="product-detail">
        <div class="detail-image">
            <span>ขายดีอันดับ 1</span>
            <img src="{{ $product->image }}" alt="{{ $product->name }}" style="max-width: 200px;">
        </div>

        <div class="detail-copy">
            <small>หน้าแรก / {{ $product->pet_type }} / {{ $product->category->name ?? 'ทั่วไป' }}</small>
            <h2>{{ $product->name }}</h2>
            <div class="rating">★★★★★ 4.9 (128 รีวิว) <i>ขายแล้ว 1,250 ชิ้น</i></div>
            <div class="big-price">฿{{ number_format($product->price) }}</div>
            <b class="stock">● มีสินค้าพร้อมส่ง</b>

            <h3>รายละเอียดสินค้า</h3>
            <p>{{ $product->description ?? 'สินค้าคุณภาพดี ปลอดภัยสำหรับสัตว์เลี้ยงที่คุณรัก' }}</p>

            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label>จำนวน</label>
                <div class="quantity">
                    <input type="number" name="quantity" value="1" min="1" style="width: 60px; text-align: center; border: none;">
                </div>

                <div class="detail-actions" style="margin-top: 15px;">
                    <button type="submit">ใส่ตะกร้า</button>
                </div>
            </form>

            <div class="checks">✓ สินค้าคุณภาพ ✓ ชำระเงินปลอดภัย ✓ จัดส่งทั่วประเทศ</div>
        </div>
    </div>

    <section class="review-card">
        <div>
            <h3>คะแนนและรีวิวจากลูกค้า</h3>
            <b>4.9 / 5 ★★★★★</b>
        </div>
        <blockquote>
            “น้องกินง่าย เม็ดพอดี ส่งเร็ว แพ็กของดีมากค่ะ”
            <small>– คุณมินท์ · ซื้อสินค้าจริง</small>
        </blockquote>
    </section>
</main>
@endsection