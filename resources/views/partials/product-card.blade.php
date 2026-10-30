{{-- การ์ดสินค้า ใช้ร่วมกันในหน้าแรก หน้าสินค้า และหน้ารายการโปรด
     ต้องส่ง $product มา (ควรโหลดด้วย Product::withStats() เพื่อให้มีดาวเฉลี่ย) --}}
@php
    $isFavorite = auth()->check() && in_array($product->id, $favoriteIds ?? []);
@endphp
<article class="product-card">
    <div class="product-visual" style="background: {{ $product->cardColor() }}; cursor: pointer;"
         onclick="window.location='{{ route('products.show', $product) }}'">
        @if($product->badge)
            <span class="badge">{{ $product->badge }}</span>
        @endif
        <span class="product-emoji">
            @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: contain;">
            @else
                {{ $product->emoji() }}
            @endif
        </span>
        <button type="button" class="favorite {{ $isFavorite ? 'selected' : '' }}"
                onclick="event.stopPropagation(); toggleFavorite({{ $product->id }}, this);"
                aria-label="บันทึกรายการโปรด">{{ $isFavorite ? '♥' : '♡' }}</button>
    </div>

    <a href="{{ route('products.show', $product) }}" class="product-copy">
        <small>{{ $product->category->name ?? 'ทั่วไป' }}{{ $product->petTypeLabel() ? ' · ' . $product->petTypeLabel() : '' }}</small>
        <strong>{{ $product->name }}</strong>
        <span class="price">
            ฿{{ number_format($product->price) }}
            @if($product->hasDiscount())
                <del>฿{{ number_format($product->original_price) }}</del>
            @endif
        </span>
    </a>

    @if(empty($hideFoot))
        <div class="product-foot">
            <span>★ {{ $product->averageRating() > 0 ? number_format($product->averageRating(), 1) : 'ใหม่' }}</span>
            <small>{{ $product->isInStock() ? ($product->brand ?? '') : 'สินค้าหมด' }}</small>
        </div>
    @endif
    <a href="{{ route('products.show', $product) }}" class="detail-link">ดูรายละเอียดสินค้า ↗</a>
</article>
