@extends('layouts.app')

{{-- หน้า 04 — รายละเอียดสินค้า --}}
@section('content')
@php
    $rating = $product->averageRating();
    $stars = str_repeat('★', (int) round($rating)) . str_repeat('☆', 5 - (int) round($rating));
    $isFavorite = in_array($product->id, $favoriteIds ?? []);
@endphp
<main class="cream-page product-page">
    <span class="orange-kicker">PRODUCT DETAILS</span>
    <h1>{{ $product->name }}</h1>
    <p class="lead">{{ $product->short_description }}</p>

    <div class="product-detail">
        <div class="detail-image">
            @if($product->badge)
                <span>{{ $product->badge }}</span>
            @endif
            @if($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" style="max-width: 200px;">
            @else
                <b style="font-size: 96px;">{{ $product->emoji() }}</b>
            @endif
        </div>

        <div class="detail-copy">
            <small>
                <a href="{{ route('home') }}">หน้าแรก</a> /
                <a href="{{ route('products.index', ['pet' => $product->pet_type]) }}">{{ $product->petTypeLabel() }}</a> /
                {{ $product->category->name ?? 'ทั่วไป' }}
            </small>
            <h2>{{ $product->name }}</h2>
            <div class="rating">
                @if($product->reviews_count > 0)
                    {{ $stars }} {{ number_format($rating, 1) }} ({{ $product->reviews_count }} รีวิว)
                @else
                    ยังไม่มีรีวิว
                @endif
                <i>ขายแล้ว {{ number_format($product->soldCount()) }} ชิ้น</i>
            </div>

            <div class="big-price">
                ฿{{ number_format($product->price) }}
                @if($product->hasDiscount())
                    <del>฿{{ number_format($product->original_price) }}</del>
                @endif
            </div>

            @if($product->isInStock())
                <b class="stock">● มีสินค้าพร้อมส่ง ({{ $product->stock }} ชิ้น)</b>
            @else
                <b class="stock out">● สินค้าหมด</b>
            @endif

            <h3>รายละเอียดสินค้า</h3>
            <p>{{ $product->description }}</p>

            {{-- ข้อมูลตามประเภทสินค้า (EER: FOOD มี flavor / ACCESSORY มี material) --}}
            <ul class="spec-list">
                @if($product->brand)<li><span>แบรนด์</span>{{ $product->brand }}</li>@endif
                @if($product->size)<li><span>ขนาด</span>{{ $product->size }}</li>@endif
                @if($product->isFood() && $product->flavor)<li><span>รสชาติ</span>{{ $product->flavor }}</li>@endif
                @if(! $product->isFood() && $product->material)<li><span>วัสดุ</span>{{ $product->material }}</li>@endif
            </ul>

            @if($product->isInStock())
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label>จำนวน</label>
                    <div class="quantity">
                        <button type="button" onclick="const i=this.nextElementSibling; i.value=Math.max(1, +i.value-1)">−</button>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                        <button type="button" onclick="const i=this.previousElementSibling; i.value=Math.min({{ $product->stock }}, +i.value+1)">+</button>
                    </div>

                    <div class="detail-actions">
                        <button type="submit" name="action" value="buy_now">ซื้อเลย</button>
                        <button type="submit" name="action" value="cart">🛒 ใส่ตะกร้า</button>
                    </div>
                </form>
            @endif

            @auth
                <form action="{{ route('favorite.toggle', $product) }}" method="POST" class="detail-favorite">
                    @csrf
                    <button type="submit">{{ $isFavorite ? '♥ อยู่ในรายการโปรดแล้ว' : '♡ เพิ่มในรายการโปรด' }}</button>
                </form>
            @endauth

            <div class="checks">✓ สินค้าคุณภาพ ✓ ชำระเงินปลอดภัย ✓ จัดส่งทั่วประเทศ</div>
        </div>
    </div>

    {{-- รีวิว + ดาว (extra feature) --}}
    <section class="review-card" id="reviews">
        <div>
            <h3>คะแนนและรีวิวจากลูกค้า</h3>
            @if($product->reviews_count > 0)
                <b>{{ number_format($rating, 1) }} / 5 {{ $stars }}</b>
                <small>{{ $product->reviews_count }} รีวิว</small>
            @else
                <small>ยังไม่มีรีวิว เป็นคนแรกที่รีวิวสินค้านี้</small>
            @endif
        </div>

        <div class="review-list">
            @foreach($reviews as $review)
                <blockquote>
                    <span class="review-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    “{{ $review->comment ?: 'ให้คะแนน ' . $review->rating . ' ดาว' }}”
                    <small>– คุณ{{ $review->user->name }} · {{ $review->created_at->format('d/m/Y') }}</small>
                </blockquote>
            @endforeach

            @auth
                <form action="{{ route('reviews.store', $product) }}" method="POST" class="review-form">
                    @csrf
                    <b>{{ $myReview ? 'แก้ไขรีวิวของคุณ' : 'เขียนรีวิว' }}</b>
                    <div class="star-input">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                   {{ (int) old('rating', $myReview->rating ?? 5) === $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}" title="{{ $i }} ดาว">★</label>
                        @endfor
                    </div>
                    <textarea name="comment" rows="3" placeholder="เล่าให้เพื่อน ๆ ฟังหน่อยว่าน้องชอบไหม...">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                    <button type="submit">ส่งรีวิว</button>
                </form>
            @else
                <p class="review-login"><a href="{{ route('login') }}">เข้าสู่ระบบ</a> เพื่อเขียนรีวิว</p>
            @endauth
        </div>
    </section>
</main>
@endsection
