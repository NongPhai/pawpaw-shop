<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. สร้างบัญชีทดสอบ
        User::create([
            'name' => 'Admin PawPaw',
            'email' => 'admin@pawpaw.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'Somchai Jaidee',
            'email' => 'customer@pawpaw.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '0812345678',
            'address' => '123/45 ถนนเชียงใหม่-หางดง อ.เมือง จ.เชียงใหม่ 50200',
        ]);

        // 2. สร้างหมวดหมู่สินค้า
        $foodCat = Category::create(['name' => 'อาหารสุนัขและแมว']);
        $toyCat = Category::create(['name' => 'ของเล่นสัตว์เลี้ยง']);
        $snackCat = Category::create(['name' => 'ขนมและทานเล่น']);
        $accessoryCat = Category::create(['name' => 'อุปกรณ์และของใช้']);

        // 3. สร้างสินค้าตัวอย่าง
        Product::create([
            'name' => 'อาหารแมวพรีเมียมสูตรกลมกล่อม 1.2kg',
            'description' => 'อาหารแมวเกรดพรีเมียม อุดมด้วยโปรตีนสูง บำรุงขนให้นุ่มสวยงาม',
            'price' => 350.00,
            'image' => 'https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=500',
            'pet_type' => 'cat',
            'category_id' => $foodCat->id,
        ]);

        Product::create([
            'name' => 'ไม้ตกแมวขนนกเด้งดึ๋ง',
            'description' => 'ของเล่นแมวยอดฮิต กระตุ้นการออกกำลังกายของน้องแมว',
            'price' => 89.00,
            'image' => 'https://images.unsplash.com/photo-1545249390-6bdfa286032f?w=500',
            'pet_type' => 'cat',
            'category_id' => $toyCat->id,
        ]);

        Product::create([
            'name' => 'อาหารสุนัขพันธุ์เล็ก รสตับไก่ 3kg',
            'description' => 'เหมาะสำหรับสุนัขพันธุ์เล็กทุกสายพันธุ์ เม็ดเล็กเคี้ยวง่าย',
            'price' => 420.00,
            'image' => 'https://images.unsplash.com/photo-1568640347023-a616a30bc3bd?w=500',
            'pet_type' => 'dog',
            'category_id' => $foodCat->id,
        ]);

        Product::create([
            'name' => 'เบาะนอนนุ่มฟูสำหรับสัตว์เลี้ยง (Size M)',
            'description' => 'เบาะนอนซักทำความสะอาดง่าย ผ้านุ่มระบายอากาศได้ดี',
            'price' => 290.00,
            'image' => 'https://images.unsplash.com/photo-1541599540903-216a46ca1dc0?w=500',
            'pet_type' => 'dog',
            'category_id' => $accessoryCat->id,
        ]);
    }
}