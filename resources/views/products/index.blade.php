@extends('layouts.app')

@section('content')
<main class="listing-shell">
    <span class="section-kicker">FIND THEIR FAVORITE</span>
    <h1>ค้นหาของให้น้อง</h1>
    <p>อาหาร ของเล่น และของใช้ที่เหมาะกับเพื่อนขนฟูของคุณ</p>

    <form class="wide-search" action="{{ route('products.index') }}" method="GET">
        <input name="search" value="{{ request('search') }}" placeholder="พิมพ์ชื่อสินค้าประเภทที่กำลังตามหา..." />
        <button type="submit">ค้นหา</button>
    </form>

    <div class="filters">
        <a href="{{ route('products.index') }}" class="{{ !request('pet') ? 'active' : '' }}">ทั้งหมด</a>
        <a href="{{ route('products.index', ['pet' => 'dog']) }}" class="{{ request('pet') == 'dog' ? 'active' : '' }}">สุนัข</a>
        <a href="{{ route('products.index', ['pet' => 'cat']) }}" class="{{ request('pet') == 'cat' ? 'active' : '' }}">แมว</a>
    </div>

    <small class="result-count">สินค้าที่แนะนำสำหรับน้อง ๆ · {{ $products->count() }} รายการ</small>

    <div class="product-grid listing-grid">
        @foreach($products as $product)
            <article class="product-card">
                <div class="product-visual" style="background: #e5f3ff;" onclick="window.location='{{ route('products.show', $product->id) }}'">
                    <span class="badge">แนะนำ</span>
                    <span class="product-emoji">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: contain;">
                    </span>
                    <button class="favorite">♡</button>
                </div>
                <a href="{{ route('products.show', $product->id) }}" class="product-copy">
                    <small>{{ $product->category->name ?? 'สินค้าทั่วไป' }}</small>
                    <strong>{{ $product->name }}</strong>
                    <span class="price">฿{{ number_format($product->price) }}</span>
                </a>
                <div class="product-foot">
                    <span>★ 4.8</span>
                    <small>PawPals</small>
                </div>
                <a href="{{ route('products.show', $product->id) }}" class="detail-link">ดูรายละเอียดสินค้า ↗</a>
            </article>
        @endforeach
    </div>
</main>
@endsection