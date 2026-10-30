<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// หน้าแรก (01)
Route::get('/', [HomeController::class, 'index'])->name('home');

// สินค้า (02 ค้นหา, 04 รายละเอียด) — ดูได้โดยไม่ต้องล็อกอิน
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// starter kit ส่งไป /dashboard หลังล็อกอิน → พาไปหน้าบัญชีแทน
Route::redirect('/dashboard', '/profile')->name('dashboard');

// ต้องเข้าสู่ระบบก่อน
Route::middleware(['auth'])->group(function () {
    // ตะกร้าสินค้า (05)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

    // สรุปคำสั่งซื้อ (06)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // รายการโปรด (03)
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorite/{product}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    // รีวิว + ดาว
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // บัญชีของฉัน (09)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address');
    Route::get('/orders/{order}', [ProfileController::class, 'order'])->name('orders.show');
});
