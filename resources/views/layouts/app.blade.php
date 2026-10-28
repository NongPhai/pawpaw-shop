<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPaw Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- 1. ดึงฟอนต์ Prompt จาก Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- 2. บังคับให้ทุกส่วนของเว็บใช้ฟอนต์นี้ -->
    <style>
        body, .app, h1, h2, h3, h4, h5, h6, p, span, a, button, input, small, strong, b, em, i {
            font-family: 'Prompt', sans-serif !important;
        }
    </style>
</head>
<body>
    @php
        $isForestTheme = in_array(Route::currentRouteName(), ['products.show', 'cart.index', 'checkout.index', 'login', 'register', 'profile']);
    @endphp

    <div class="app {{ $isForestTheme ? 'forest-theme' : 'violet-theme' }}">
        <!-- Header -->
        <header class="site-header {{ $isForestTheme ? 'forest' : '' }}">
            <div class="header-inner">
                <a href="{{ route('home') }}" class="logo">
                    <span>p</span>
                    <b>PawPaw</b>
                </a>

                <nav>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">หน้าแรก</a>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">สินค้า</a>
                    <a href="{{ route('products.index', ['favorite' => 1]) }}">♡ รายการโปรด</a>
                </nav>

                <div class="header-actions">
                    @if($isForestTheme)
                        @auth
                            <a href="{{ route('cart.index') }}" class="cart-pill">
                                🛒 ตะกร้าสินค้า
                            </a>
                            <a href="{{ route('profile') }}" class="login-pill">บัญชีของฉัน</a>
                        @else
                            <a href="{{ route('login') }}" class="login-pill">เข้าสู่ระบบ</a>
                        @endauth
                    @else
                        @auth
                            <a href="{{ route('profile') }}" class="avatar">
                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                            </a>
                            <a href="{{ route('profile') }}" class="account-text">บัญชีของฉัน</a>
                        @else
                            <a href="{{ route('login') }}" class="account-text">เข้าสู่ระบบ</a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        @yield('content')

        <!-- Footer -->
        <footer>
            <a href="{{ route('home') }}" class="logo">
                <span>p</span>
                <b>PawPaw</b>
            </a>
            <span>เพื่อนคู่ใจของสัตว์เลี้ยงทุกตัว © 2026</span>
            <div>
                <a href="{{ route('products.index') }}">เลือกซื้อสินค้า</a>
                <a href="{{ route('profile') }}">เคล็ดลับดูแลน้อง</a>
            </div>
        </footer>
    </div>
</body>
</html>