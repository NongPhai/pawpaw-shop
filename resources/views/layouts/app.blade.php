<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' · ' : '' }}PawPaw Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ฟอนต์ตาม Figma: IBM Plex Sans Thai (หัวข้อ 700, เนื้อหา 400) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@400;500;600;700&family=Inter:wght@700&display=swap" rel="stylesheet">
    <style>
        body, .app, h1, h2, h3, h4, h5, h6, p, span, a, button, input, select, textarea, small, strong, b, em, i {
            font-family: 'IBM Plex Sans Thai', sans-serif !important;
        }
    </style>
</head>
<body>
    @php
        // ทั้งเว็บใช้ธีมเขียวสีเดียวกับโลโก้ (เดิม Figma แยกม่วง/เขียว)
        $isForestTheme = true;
    @endphp

    <div class="app {{ $isForestTheme ? 'forest-theme' : 'violet-theme' }}">
        <header class="site-header {{ $isForestTheme ? 'forest' : 'violet' }}">
            <div class="header-inner">
                {{-- โลโก้: ธีมม่วงใช้ตัว p, ธีมเขียวใช้รูปอุ้งเท้า (ตาม Figma) --}}
                <a href="{{ route('home') }}" class="logo">
                    <span class="logo-mark">
                        @if($isForestTheme)
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="#fff" aria-hidden="true"><ellipse cx="6" cy="9" rx="2.2" ry="2.8"/><ellipse cx="10" cy="5.5" rx="2.2" ry="2.8"/><ellipse cx="14.5" cy="5.5" rx="2.2" ry="2.8"/><ellipse cx="18.5" cy="9" rx="2.2" ry="2.8"/><path d="M12.2 10.5c-3 0-6.2 4.3-6.2 6.7 0 1.7 1.3 2.6 3 2.6 1.4 0 2.1-.7 3.2-.7s1.8.7 3.2.7c1.7 0 3-.9 3-2.6 0-2.4-3.2-6.7-6.2-6.7z"/></svg>
                        @else
                            p
                        @endif
                    </span>
                    <b>PawPaw</b>
                </a>

                <nav>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">หน้าแรก</a>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">สินค้า</a>
                    <a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}">♡ รายการโปรด</a>
                </nav>

                <div class="header-actions">
                    @if($isForestTheme)
                        {{-- ธีมเขียว: ปุ่มตะกร้า (เขียวอ่อน) + ปุ่มบัญชี/เข้าสู่ระบบ (เขียวเข้ม) --}}
                        <a href="{{ route('cart.index') }}" class="cart-pill">
                            🛒 ตะกร้าสินค้า@if($cartCount > 0)<span class="cart-count">{{ $cartCount }}</span>@endif
                        </a>
                        @auth
                            <a href="{{ route('profile') }}" class="login-pill">บัญชีของฉัน</a>
                        @else
                            <a href="{{ route('login') }}" class="login-pill">เข้าสู่ระบบ</a>
                        @endauth
                    @else
                        {{-- ธีมม่วง: ปุ่มกลม (รายการโปรด, ตะกร้า) + รูปโปรไฟล์ + "บัญชีของฉัน" --}}
                        <a href="{{ route('favorites.index') }}" class="round-action" title="รายการโปรด">♡</a>
                        <a href="{{ route('cart.index') }}" class="round-action" title="ตะกร้าสินค้า">
                            🛒@if($cartCount > 0)<span class="cart-count">{{ $cartCount }}</span>@endif
                        </a>
                        @auth
                            <a href="{{ route('profile') }}" class="user-menu">
                                <span class="avatar">{{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                                <span class="account-text">บัญชีของฉัน</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="user-menu">
                                <span class="account-text">เข้าสู่ระบบ</span>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        {{-- ข้อความแจ้งเตือนหลังทำรายการ (สำเร็จ / ผิดพลาด) --}}
        @if(session('success') || session('error') || $errors->any())
            <div class="flash-wrap">
                @if(session('success'))
                    <div class="flash success">✓ {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="flash error">! {{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="flash error">
                        @foreach($errors->all() as $message)
                            <div>! {{ $message }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        @yield('content')

        <footer>
            <a href="{{ route('home') }}" class="logo">
                <span><svg viewBox="0 0 24 24" width="16" height="16" fill="#fff" aria-hidden="true"><ellipse cx="6" cy="9" rx="2.2" ry="2.8"/><ellipse cx="10" cy="5.5" rx="2.2" ry="2.8"/><ellipse cx="14.5" cy="5.5" rx="2.2" ry="2.8"/><ellipse cx="18.5" cy="9" rx="2.2" ry="2.8"/><path d="M12.2 10.5c-3 0-6.2 4.3-6.2 6.7 0 1.7 1.3 2.6 3 2.6 1.4 0 2.1-.7 3.2-.7s1.8.7 3.2.7c1.7 0 3-.9 3-2.6 0-2.4-3.2-6.7-6.2-6.7z"/></svg></span>
                <b>PawPaw</b>
            </a>
            <span>เพื่อนคู่ใจของสัตว์เลี้ยงทุกตัว © 2026</span>
            <div>
                <a href="{{ route('products.index') }}">เลือกซื้อสินค้า</a>
                <a href="{{ route('favorites.index') }}">รายการโปรด</a>
            </div>
        </footer>
    </div>

    <script>
    // ปุ่มหัวใจบนการ์ดสินค้า: เพิ่ม/เอาออกจากรายการโปรด โดยไม่ต้องโหลดหน้าใหม่
    function toggleFavorite(productId, button) {
        fetch(`/favorite/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = '{{ route('login') }}';
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;
            button.classList.toggle('selected', data.favorited);
            button.textContent = data.favorited ? '♥' : '♡';
            // อยู่หน้ารายการโปรดแล้วกดเอาออก → ซ่อนการ์ดนั้น
            if (!data.favorited && button.closest('[data-favorites-page]')) {
                button.closest('.product-card').remove();
            }
        })
        .catch(error => console.error(error));
    }
    </script>
</body>
</html>
