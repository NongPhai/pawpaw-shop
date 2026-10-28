@extends('layouts.app')

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
                <input type="email" name="email" placeholder="กรอกอีเมล" required value="{{ old('email') }}">
            </label>
            <label>
                <span>รหัสผ่าน</span>
                <input type="password" name="password" placeholder="กรอกรหัสผ่าน" required>
            </label>
        </div>

        <button type="submit" class="auth-submit" style="margin-top: 20px;">เข้าสู่ระบบ</button>

        <div class="switch-auth" style="margin-top: 20px;">
            ยังไม่มีบัญชีใช่ไหม?
            <a href="{{ route('register') }}" style="color: #ea580c; font-weight: bold; text-decoration: none;">สมัครสมาชิก</a>
        </div>
    </form>
</main>
@endsection