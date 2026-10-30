@extends('layouts.app')

{{-- หน้า 08 — เข้าสู่ระบบ (Fortify จัดการล็อกอินให้ ใช้อีเมล + รหัสผ่าน) --}}
@section('content')
<main class="auth-page">
    <section class="auth-art">
        <div>🐕 🐈</div>
        <h2>ความสุขของน้องเริ่มต้นที่นี่</h2>
        <p>ของใช้ที่ใช่ สำหรับเพื่อนสี่ขาที่คุณรัก</p>
    </section>

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf
        <span class="orange-kicker">WELCOME BACK</span>
        <h1>ยินดีต้อนรับกลับ</h1>
        <p>เข้าสู่ระบบเพื่อดูคำสั่งซื้อและรายการโปรด</p>

        <div class="single-fields">
            <label>
                <span>อีเมล</span>
                <input type="email" name="email" placeholder="กรอกอีเมล" required autofocus value="{{ old('email') }}">
            </label>
            <label>
                <span>รหัสผ่าน</span>
                <input type="password" name="password" placeholder="กรอกรหัสผ่าน" required>
            </label>
        </div>

        <div class="login-extra">
            <label><input type="checkbox" name="remember"> จดจำฉันไว้</label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}">ลืมรหัสผ่าน?</a>
            @endif
        </div>

        <button type="submit" class="auth-submit">เข้าสู่ระบบ</button>

        <div class="switch-auth">
            ยังไม่มีบัญชีใช่ไหม?
            <a href="{{ route('register') }}">สมัครสมาชิก</a>
        </div>
    </form>
</main>
@endsection
