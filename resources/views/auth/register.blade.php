@extends('layouts.app')

{{-- หน้า 07 — สมัครสมาชิก (บันทึกผ่าน app/Actions/Fortify/CreateNewUser.php) --}}
@section('content')
<main class="auth-page signup">
    <section class="auth-art">
        <div>🐶 🐱</div>
        <h2>เริ่มต้นดูแลน้องไปด้วยกัน</h2>
        <p>สมัครสมาชิก PawPaw เพื่อเก็บที่อยู่ ติดตามคำสั่งซื้อ และดูรายการโปรด</p>
    </section>

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf
        <span class="orange-kicker">WELCOME TO PAWPAW</span>
        <h1>สมัครสมาชิก</h1>
        <p>สร้างบัญชีเพื่อช้อปของให้น้องได้สะดวกขึ้น</p>

        <div class="form-grid">
            <label>
                <span>ชื่อจริง</span>
                <input type="text" name="name" placeholder="กรอกชื่อจริง" required value="{{ old('name') }}">
            </label>
            <label>
                <span>นามสกุล</span>
                <input type="text" name="last_name" placeholder="กรอกนามสกุล" required value="{{ old('last_name') }}">
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

        <label class="terms">
            <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
            ฉันยอมรับเงื่อนไขการใช้งานและนโยบายความเป็นส่วนตัว
        </label>

        <button type="submit" class="auth-submit">สมัครสมาชิก</button>

        <div class="switch-auth">
            มีบัญชีอยู่แล้ว?
            <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
        </div>
    </form>
</main>
@endsection
