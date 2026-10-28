@extends('layouts.app')

@section('content')
<main class="cream-page profile-page">
    <span class="orange-kicker">MY ACCOUNT</span>
    <h1>บัญชีของฉัน</h1>
    <p class="lead">จัดการข้อมูลส่วนตัว ที่อยู่จัดส่ง และติดตามประวัติคำสั่งซื้อ</p>

    <div class="profile-layout">
        <aside class="profile-nav">
            <div class="profile-avatar">🐾</div>
            <b>{{ Auth::user()->name }}</b>
            <small>{{ Auth::user()->email }}</small>
            <button class="active">ข้อมูลส่วนตัว</button>
            
            <form action="{{ route('logout') }}" method="POST" style="margin-top: 20px;">
                @csrf
                <button type="submit" class="logout">ออกจากระบบ</button>
            </form>
        </aside>

        <div class="profile-content">
            <section class="profile-card">
                <h2>ข้อมูลส่วนตัว</h2>
                <div class="form-grid">
                    <label><span>ชื่อ-นามสกุล</span><input value="{{ Auth::user()->name }}" readonly></label>
                    <label><span>อีเมล</span><input value="{{ Auth::user()->email }}" readonly></label>
                    <label><span>เบอร์โทรศัพท์</span><input value="{{ Auth::user()->phone ?? '-' }}" readonly></label>
                </div>
            </section>

            <section class="profile-card address">
                <div>
                    <h2>ที่อยู่จัดส่ง</h2>
                    <p>{{ Auth::user()->address ?? 'ยังไม่ได้ระบุที่อยู่จัดส่ง' }}</p>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection