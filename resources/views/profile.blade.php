@extends('layouts.app')

{{-- หน้า 09 — บัญชีของฉัน --}}
@section('content')
<main class="cream-page profile-page">
    <span class="orange-kicker">MY ACCOUNT</span>
    <h1>บัญชีของฉัน</h1>
    <p class="lead">จัดการข้อมูลส่วนตัว ที่อยู่จัดส่ง และติดตามประวัติคำสั่งซื้อ</p>

    <div class="profile-layout">
        <aside class="profile-nav">
            <div class="profile-avatar">🐾</div>
            <b>คุณ{{ $user->fullName() }}</b>
            <small>{{ $user->email }}</small>
            <a href="#info" class="profile-link active">👤 ข้อมูลส่วนตัว</a>
            <a href="#address" class="profile-link">⌂ ที่อยู่จัดส่ง</a>
            <a href="#orders" class="profile-link">▤ ประวัติคำสั่งซื้อ</a>
            <a href="{{ route('favorites.index') }}" class="profile-link">♡ รายการโปรด</a>

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout">ออกจากระบบ</button>
            </form>
        </aside>

        <div class="profile-content">
            <section class="profile-card" id="info">
                <h2>ข้อมูลส่วนตัว</h2>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="form-grid">
                        <label><span>ชื่อจริง</span>
                            <input name="name" value="{{ old('name', $user->name) }}" placeholder="ชื่อจริง" required></label>
                        <label><span>นามสกุล</span>
                            <input name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="นามสกุล"></label>
                        <label><span>เบอร์โทรศัพท์</span>
                            <input name="phone" value="{{ old('phone', $user->phone) }}" placeholder="เบอร์โทรศัพท์"></label>
                        <label><span>อีเมล</span>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="อีเมล" required></label>
                    </div>
                    <button type="submit" class="profile-save">บันทึกการเปลี่ยนแปลง</button>
                </form>
            </section>

            <section class="profile-card" id="address">
                <h2>ที่อยู่จัดส่ง</h2>
                <p class="address-now">
                    {{ $user->address ? 'คุณ' . $user->fullName() . ' · ' . $user->fullAddress() : 'ยังไม่ได้ระบุที่อยู่จัดส่ง' }}
                </p>
                <details {{ $user->address ? '' : 'open' }}>
                    <summary>+ เพิ่ม / แก้ไขที่อยู่</summary>
                    <form action="{{ route('profile.address') }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="form-grid">
                            <label class="wide"><span>ที่อยู่</span>
                                <input name="address" value="{{ old('address', $user->address) }}" placeholder="บ้านเลขที่ / ถนน / แขวง / เขต" required></label>
                            <label><span>จังหวัด</span>
                                <input name="province" value="{{ old('province', $user->province) }}" required></label>
                            <label><span>รหัสไปรษณีย์</span>
                                <input name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" maxlength="10" required></label>
                        </div>
                        <button type="submit" class="profile-save">บันทึกที่อยู่</button>
                    </form>
                </details>
            </section>

            <section class="profile-card" id="orders">
                <h2>ประวัติคำสั่งซื้อล่าสุด</h2>
                @forelse($orders as $order)
                    <div class="order-row">
                        <div>
                            <b>คำสั่งซื้อ {{ $order->number() }}</b>
                            <small>วันที่ {{ $order->created_at->format('d/m/Y H:i') }} · {{ $order->products_count }} รายการ</small>
                            <a href="{{ route('orders.show', $order) }}">ดูรายละเอียดคำสั่งซื้อ →</a>
                        </div>
                        <span class="order-status status-{{ $order->status }}">● {{ $order->statusLabel() }}</span>
                        <b>฿{{ number_format($order->total) }}</b>
                    </div>
                @empty
                    <p class="address-now">ยังไม่มีคำสั่งซื้อ <a href="{{ route('products.index') }}">เริ่มช้อปเลย →</a></p>
                @endforelse
            </section>
        </div>
    </div>
</main>
@endsection
