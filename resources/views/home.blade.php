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
                <strong>เริ่ม ฿129</strong>
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
        <a href="{{ route('products.index', ['pet' => 'dog']) }}" style="background: #efeaff;">
            <i>🐕</i>
            <b>สุนัข</b>
            <small>อาหาร · สายจูง · ของเล่น</small>
        </a>
        <a href="{{ route('products.index', ['pet' => 'cat']) }}" style="background: #e7f3ff;">
            <i>🐈</i>
            <b>แมว</b>
            <small>ทรายแมว · คอนโด · ขนม</small>
        </a>
        <a href="{{ route('products.index', ['pet' => 'rabbit']) }}" style="background: #fff0e7;">
            <i>🦭</i>
            <b>แมวและสัตว์เล็ก</b>
            <small>กรง · อาหาร · ของเล่น</small>
        </a>
        <a href="{{ route('products.index', ['pet' => 'other']) }}" style="background: #e5f8ef;">
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
        <article class="product-card">
            <!-- ส่วนรูปภาพ (คลิกแล้วไปหน้าดูรายละเอียด) -->
            <div class="product-visual" style="background: #eee8ff; position: relative; cursor: pointer;" onclick="window.location='{{ route('products.show', $product->id) }}'">
                <span class="badge">ขายดี</span>
                <span class="product-emoji">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: contain;">
                </span>
                
                <!-- ปุ่มหัวใจ: ใส่ style="z-index: 10; position: absolute;" เพื่อให้คลิกโดนปุ่มนี้โดยตรง -->
                <button type="button" 
                        class="favorite {{ auth()->check() && auth()->user()->favorites && auth()->user()->favorites->contains($product->id) ? 'selected' : '' }}" 
                        style="z-index: 10; position: absolute; top: 9px; right: 9px;"
                        onclick="event.stopPropagation(); event.preventDefault(); toggleFavorite({{ $product->id }}, this);" 
                        aria-label="บันทึกรายการโปรด">
                    ♡
                </button>
            </div>

            <a href="{{ route('products.show', $product->id) }}" class="product-copy">
                <small>{{ $product->category->name ?? 'ทั่วไป' }}</small>
                <strong>{{ $product->name }}</strong>
                <span class="price">฿{{ number_format($product->price) }}</span>
            </a>
            <div class="product-foot">
                <span>★ 4.9</span>
                <small>PawPals</small>
            </div>
            <a href="{{ route('products.show', $product->id) }}" class="detail-link">ดูรายละเอียดสินค้า ↗</a>
        </article>
    @endforeach
</div>
</main>

<script>
function toggleFavorite(productId, buttonElement) {
    fetch(`/favorite/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.status === 401) {
            alert('กรุณาเข้าสู่ระบบก่อนบันทึกรายการโปรด');
            window.location.href = '{{ route("login") }}';
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data) {
            if (data.favorited) {
                buttonElement.classList.add('selected');
            } else {
                buttonElement.classList.remove('selected');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection