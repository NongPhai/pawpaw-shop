@extends('layouts.app')

{{-- หน้า 02 — ค้นหาของให้น้อง --}}
@section('content')
<main class="listing-shell">
    <span class="section-kicker">FIND THEIR FAVORITE</span>
    <h1>ค้นหาของให้น้อง</h1>
    <p>อาหาร ของเล่น และของใช้ที่เหมาะกับเพื่อนขนฟูของคุณ</p>

    <form class="wide-search" action="{{ route('products.index') }}" method="GET">
        @if(request('pet'))
            <input type="hidden" name="pet" value="{{ request('pet') }}">
        @endif
        <input name="search" value="{{ request('search') }}" placeholder="พิมพ์ชื่อสินค้า ประเภทสัตว์ หรือแบรนด์..." />
        <button type="submit">ค้นหา</button>
    </form>

    <div class="filters">
        <a href="{{ route('products.index', array_filter(['search' => request('search')])) }}"
           class="{{ ! request('pet') ? 'active' : '' }}">ทั้งหมด</a>
        @foreach(\App\Models\Product::PET_TYPES as $key => $label)
            <a href="{{ route('products.index', array_filter(['pet' => $key, 'search' => request('search')])) }}"
               class="{{ request('pet') === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <small class="result-count">
        @if(request('search'))
            ผลการค้นหา "{{ request('search') }}" · {{ $products->count() }} รายการ
        @else
            สินค้าแนะนำสำหรับน้อง ๆ · {{ $products->count() }} รายการ
        @endif
    </small>

    <div class="product-grid listing-grid">
        @forelse($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="empty-state">
                <b>ไม่พบสินค้าที่ค้นหา</b>
                <small>ลองใช้คำค้นหาอื่น หรือ <a href="{{ route('products.index') }}">ดูสินค้าทั้งหมด</a></small>
            </div>
        @endforelse
    </div>
</main>
@endsection
