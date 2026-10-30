<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' · ' : '' }}PawPaw Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ฟอนต์ Prompt จาก Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body, .app, h1, h2, h3, h4, h5, h6, p, span, a, button, input, select, textarea, small, strong, b, em, i {
            font-family: 'Prompt', sans-serif !important;
        }
    </style>
</head>
<body>
    @php
        // Figma: หน้าแรก / ค้นหา / รายการโปรด ใช้ธีมม่วง, หน้าที่เหลือใช้ธีมเขียว
        $isForestTheme = ! request()->routeIs('home', 'products.index', 'favorites.index');
    @endphp

    <div class="app {{ $isForestTheme ? 'forest-theme' : 'violet-theme' }}">
        <header class="site-header {{ $isForestTheme ? 'forest' : '' }}">
            <div class="header-inner">
                <a href="{{ route('home') }}" class="logo">
                    <span>p</span>
                    <b>PawPaw</b>
                </a>

                <nav>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">หน้าแรก</a>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">สินค้า</a>
                    <a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}">♡ รายการโปรด</a>
                </nav>

                <div class="header-actions">
                    @auth
                        <a href="{{ route('cart.index') }}" class="cart-pill">
                            🛒 ตะกร้าสินค้า{{ $cartCount > 0 ? " ({$cartCount})" : '' }}
                        </a>
                        <a href="{{ route('profile') }}" class="avatar" title="บัญชีของฉัน">
                            {{ mb_substr(Auth::user()->name, 0, 1) }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="login-pill">เข้าสู่ระบบ</a>
                    @endauth
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
                <span>p</span>
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
