<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;

// หน้าแรก (Home)
Route::get('/', [HomeController::class, 'index'])->name('home');

// หน้าสินค้า (Products)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// ดักคนที่เผลอเข้า /dashboard ให้เด้งกลับมาหน้า /profile
Route::redirect('/dashboard', '/profile');

// กลุ่ม Route ที่ต้องเข้าสู่ระบบก่อน (Auth)
Route::middleware(['auth'])->group(function () {
    // ตะกร้าสินค้า
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // สรุปคำสั่งซื้อ
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // หน้า Profile
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // ระบบรายการโปรด (Favorite)
    Route::post('/favorite/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');
});