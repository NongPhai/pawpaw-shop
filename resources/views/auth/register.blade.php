@extends('layouts.app')

@section('content')
<main class="auth-page signup">
    <section class="auth-art">
        <div>🐶 🐱</div>
        <h2>เริ่มต้นดูแลน้องไปด้วยกัน</h2>
        <p>สมัครสมาชิก PawPals เพื่อเก็บที่อยู่ ติดตามคำสั่งซื้อ และดูรายการโปรด</p>
    </section>

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf
        <span class="orange-kicker">WELCOME TO PAWPALS</span>
        <h1>สมัครสมาชิก</h1>
        <p>สร้างบัญชีเพื่อช้อปของให้น้องได้สะดวกขึ้น</p>

        <div class="form-grid">
            <label class="wide">
                <span>ชื่อ-นามสกุล</span>
                <input type="text" name="name" placeholder="กรอกชื่อ-นามสกุล" required value="{{ old('name') }}">
            </label>
            <label class="wide">
                <span>อีเมล</span>
                <input type="email" name="email" placeholder="example@email.com" required value="{{ old('email') }}">
            </label>
            <label class="wide">
                <span>เบอร์โทรศัพท์</span>
                <input type="text" name="phone" placeholder="08X-XXX-XXXX" value="{{ old('phone') }}">
            </label>
            <label>
                <span>รหัสผ่าน</span>
                <input type="password" name="password" placeholder="อย่างน้อย 8 ตัวอักษร" required>
            </label>
            <label>
                <span>ยืนยันรหัสผ่าน</span>
                <input type="password" name="password_confirmation" placeholder="กรอกรหัสผ่านอีกครั้ง" required>
            </label>
        </div>

        <button type="submit" class="auth-submit" style="margin-top: 20px;">สมัครสมาชิก</button>

        <div class="switch-auth" style="margin-top: 20px;">
            มีบัญชีอยู่แล้ว?
            <a href="{{ route('login') }}" style="color: #ea580c; font-weight: bold; text-decoration: none;">เข้าสู่ระบบ</a>
        </div>
    </form>
</main>
@endsection