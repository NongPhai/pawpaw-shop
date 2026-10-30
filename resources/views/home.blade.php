@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="hero-inner">
        <div class="hero-copy">
            <span class="eyebrow violet">✣ เพื่อนซี้แสนสุขของคุณ</span>
            <h1>
                ทุกสิ่งที่น้องรัก<br />
                <em>เราเลือกมาให้</em>
            </h1>
            <p>รวมอาหาร ของเล่น และของใช้สำหรับสัตว์เลี้ยง ให้ทุกวันของน้องมีความสุข</p>
            <form class="search-box" action="{{ route('products.index') }}" method="GET">
                <input name="search" placeholder="ค้นหาอาหาร ของเล่น หรืออุปกรณ์สัตว์เลี้ยง..." />
                <button type="submit">ค้นหา</button>
            </form>
            <div class="popular">
                <span>ยอดนิยม</span>
                <a href="{{ route('products.index', ['search' => 'อาหาร']) }}">อาหารสัตว์</a>
                <a href="{{ route('products.index', ['search' => 'ของเล่น']) }}">ของเล่น</a>
                <a href="{{ route('products.index', ['search' => 'ที่นอน']) }}">ที่นอนนุ่ม</a>
            </div>
        </div>
        <div class="hero-art">
            <span class="art-label top">✦ น้องต้องชอบแน่</span>
            <div class="arch"></div>
            <div class="recommend-card">
                <span class="mini-badge">คัดสรรเพื่อนรัก</span>
                <div>🐶</div>
                <small>ไอเทมแนะนำ</small>
                <strong>เริ่ม ฿{{ number_format($startingPrice ?? 0) }}</strong>
            </div>
            <span class="art-label bottom">♡ บันทึกไว้ดูภายหลัง</span>
        </div>
    </div>
</section>

<section class="benefits">
    <div>
        <span>♡</span>
        <p>
            <b>คุณภาพที่ใส่ใจ</b>
            <small>เลือกสินค้าคุณภาพสำหรับน้อง</small>
        </p>
    </div>
    <div>
        <span>♡</span>
        <p>
            <b>ช้อปง่ายทุกวัน</b>
            <small>รวมของใช้สัตว์เลี้ยงที่เดียว</small>
        </p>
    </div>
    <div>
        <span>⌕</span>
        <p>
            <b>ส่งความสุขให้น้อง</b>
            <small>ของดีสำหรับทุกช่วงวัย</small>
        </p>
    </div>
</section>

<main class="catalog-shell">
    <div class="section-heading">
        <div>
            <span>SHOP BY PET</span>
            <h2>เลือกช้อปตามหมวดหมู่</h2>
        </div>
        <a href="{{ route('products.index') }}">ดูทุกหมวด ↗</a>
    </div>

    <div class="category-grid">
        <a href="{{ route('products.index', ['pet' => 'dog']) }}" style="background: #ebf2e0;">
            <i>🐕</i>
            <b>สุนัข</b>
            <small>อาหาร · สายจูง · ของเล่น</small>
        </a>
        <a href="{{ route('products.index', ['pet' => 'cat']) }}" style="background: #f6efdf;">
            <i>🐈</i>
            <b>แมว</b>
            <small>ทรายแมว · คอนโด · ขนม</small>
        </a>
        <a href="{{ route('products.index', ['pet' => 'small_pet']) }}" style="background: #fdeee4;">
            <i>🐦</i>
            <b>นกและสัตว์เล็ก</b>
            <small>กรง · อาหาร · ของเล่น</small>
        </a>
        <a href="{{ route('products.index', ['pet' => 'fish']) }}" style="background: #e5f1ea;">
            <i>🐠</i>
            <b>ปลา</b>
            <small>ตู้ปลา · อาหาร · อุปกรณ์</small>
        </a>
    </div>

    <div class="section-heading products-title">
        <div>
            <span>PAW-PICKED</span>
            <h2>ของโปรดที่น้อง ๆ เลิฟ</h2>
            <p>คัดสรรของใช้และของเล่นเพื่อสุขภาพและความสุขของสัตว์เลี้ยง</p>
        </div>
        <a href="{{ route('products.index') }}">ดูสินค้าทั้งหมด ↗</a>
    </div>

    <div class="product-grid">
    @foreach($recommendedProducts as $product)
        @include('partials.product-card', ['product' => $product])
    @endforeach
</div>
</main>

@endsection