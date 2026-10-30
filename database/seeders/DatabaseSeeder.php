<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * ./vendor/bin/sail artisan db:seed
     * เรียก ShopSeeder ที่ใส่ข้อมูลตัวอย่างของร้าน (สินค้า บัญชีทดสอบ รีวิว รายการโปรด คำสั่งซื้อ)
     */
    public function run(): void
    {
        $this->call(ShopSeeder::class);
    }
}
