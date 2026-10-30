@extends('layouts.app')

{{-- หน้า 03 — รายการโปรดของน้อง (ตาราง wishlists) --}}
@section('content')
<main class="listing-shell" data-favorites-page>
    <span class="section-kicker">YOUR PET'S FAVORITES</span>
    <h1>รายการโปรดของน้อง</h1>
    <p>เก็บสินค้าที่ถูกใจไว้ดูได้ทุกเมื่อ</p>

    <div class="favorite-banner">
        <span>♡</span>
        <div>
            <b>{{ $products->count() }} รายการโปรด</b>
            <small>สินค้าที่คุณบันทึกไว้</small>
        </div>
    </div>

    <div class="product-grid listing-grid">
        @forelse($products as $product)
            @include('partials.product-card', ['product' => $product, 'hideFoot' => true])
        @empty
            <div class="empty-state">
                <b>ยังไม่มีรายการโปรด</b>
                <small><a href="{{ route('products.index') }}">ไปเลือกสินค้า</a> แล้วกดรูปหัวใจเพื่อบันทึกไว้ที่นี่</small>
            </div>
        @endforelse
    </div>

    <p class="favorites-note"><small>แตะรูปหัวใจบนสินค้าเพื่อเพิ่มหรือนำออกจากรายการโปรด</small></p>
</main>
@endsection
