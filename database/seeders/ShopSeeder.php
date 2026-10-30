<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Sample data for the demo.
 * Run: ./vendor/bin/sail artisan db:seed --class=ShopSeeder
 *
 * Demo accounts:
 *   somchai@mail.com / 123456
 *   malee@mail.com   / 123456
 */
class ShopSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command->error('Demo data is not allowed in production.');
            return;
        }
        if (Product::exists()) {
            $this->command->warn('Shop data already exists - skipped.');
            return;
        }

        // ---------- users (name = first name) ----------
        $somchai = $this->makeUser('สมชาย', 'ใจดี', 'somchai@mail.com', '123456', '0812345678',
            '99/1 ถ.นิมมานเหมินท์ อ.เมือง', 'เชียงใหม่', '50200');
        $malee = $this->makeUser('มาลี', 'รักแมว', 'malee@mail.com', '123456', '0898765432',
            '12 ซ.รังสิต 5 อ.ธัญบุรี', 'ปทุมธานี', '12130');

        // ---------- categories ----------
        $cats = [];
        foreach (['อาหาร', 'ขนม', 'ของเล่น', 'ทราย/ห้องน้ำ', 'ที่นอน/บ้าน', 'ปลอกคอ/สายจูง', 'อาบน้ำ/ดูแลขน', 'อุปกรณ์เลี้ยง'] as $name) {
            $cats[$name] = Category::create(['name' => $name]);
        }

        // ---------- products ----------
        // [name, short description, description, brand, size, price, original price, stock, badge, category, pet_type]
        $rows = [
            1 => ['อาหารสุนัขสูตรครบถ้วน', 'โภชนาการที่สมดุล เพื่อสุขภาพดีและพลังงานในทุกวัน',
                'อาหารเม็ดคุณภาพสำหรับสุนัขโต มีโปรตีนและสารอาหารจำเป็น ช่วยดูแลกล้ามเนื้อ ผิวหนัง และขน เหมาะสำหรับการกินทุกวัน',
                'Happy Tails', '2 กก.', 459, 520, 40, 'ขายดี', 'อาหาร', 'dog'],
            2 => ['ชุดลูกบอลและไม้ตกแมว', 'ของเล่นสำหรับแมวทุกวัย', 'ลูกบอลไหมพรมและไม้ตกแมวขนนก ช่วยให้น้องแมวได้ออกกำลังกาย',
                'Meow House', null, 189, 229, 35, 'ของใหม่', 'ของเล่น', 'cat'],
            3 => ['เบาะนอนนุ่มพิเศษ', 'ที่นอนสัตว์เลี้ยง นุ่มสบาย', 'เบาะนอนนุ่ม ถอดซักได้ เหมาะสำหรับสุนัขและแมวขนาดเล็กถึงกลาง',
                'Cozy Paw', 'M', 390, 450, 12, 'นุ่มสบาย', 'ที่นอน/บ้าน', 'dog'],
            4 => ['สายจูงปรับได้', 'อุปกรณ์เดินเล่น', 'สายจูงไนลอนปรับความยาวได้ จับถนัดมือ',
                'Walkies', '1.5 ม.', 259, 320, 22, 'พร้อมลุย', 'ปลอกคอ/สายจูง', 'dog'],
            5 => ['อาหารเม็ดลูกสุนัข', 'สูตรสำหรับลูกสุนัข', 'สูตรสำหรับลูกสุนัขอายุ 2-12 เดือน เม็ดเล็กเคี้ยวง่าย',
                'Happy Tails', '1.5 กก.', 320, null, 15, null, 'อาหาร', 'dog'],
            6 => ['ขนมขัดฟันสุนัข', 'ลดคราบหินปูน', 'ขนมช่วยลดคราบหินปูน ลมหายใจสดชื่น',
                'Happy Tails', null, 120, null, 40, null, 'ขนม', 'dog'],
            7 => ['อาหารเม็ดแมวโต รสปลาทูน่า', 'ช่วยบำรุงขน', 'อาหารเม็ดแมวโต รสปลาทูน่า ช่วยบำรุงขนให้นุ่มเงางาม',
                'Meow House', '1.2 กก.', 290, null, 25, null, 'อาหาร', 'cat'],
            8 => ['ขนมแมวเลีย รสแซลมอน', 'แพ็ค 4 ซอง', 'ขนมแมวเลียเนื้อครีม รสแซลมอน 4 ซอง',
                'Meow House', '4 ซอง', 69, null, 50, 'ขายดี', 'ขนม', 'cat'],
            9 => ['ทรายแมวเต้าหู้', 'จับก้อนดี ทิ้งชักโครกได้', 'ทรายเต้าหู้ จับตัวเป็นก้อน ทิ้งชักโครกได้',
                'Meow House', '6 ลิตร', 189, 219, 30, null, 'ทราย/ห้องน้ำ', 'cat'],
            10 => ['คอนโดแมว 3 ชั้น', 'พร้อมเสาลับเล็บ', 'คอนโดแมวพร้อมเสาลับเล็บ 3 ชั้น',
                'Cozy Paw', null, 1290, null, 0, null, 'ที่นอน/บ้าน', 'cat'],
            11 => ['แปรงหวีขนแมว', 'ลดขนร่วง', 'แปรงหวีขนช่วยลดขนร่วง',
                null, null, 159, null, 28, null, 'อาบน้ำ/ดูแลขน', 'cat'],
            12 => ['แชมพูสุนัข สูตรอ่อนโยน', 'สำหรับผิวแพ้ง่าย', 'แชมพูสูตรอ่อนโยน สำหรับผิวแพ้ง่าย',
                null, '500 มล.', 259, null, 16, null, 'อาบน้ำ/ดูแลขน', 'dog'],
            13 => ['อาหารนกผสมธัญพืช', 'สำหรับนกขนาดเล็ก', 'อาหารนกผสมธัญพืช 5 ชนิด สำหรับนกแก้วและนกขนาดเล็ก',
                null, '1 กก.', 129, null, 20, null, 'อาหาร', 'small_pet'],
            14 => ['กรงแฮมสเตอร์ 2 ชั้น', 'พร้อมล้อวิ่ง', 'กรงแฮมสเตอร์ 2 ชั้น พร้อมล้อวิ่งและขวดน้ำ',
                null, null, 690, 790, 8, null, 'อุปกรณ์เลี้ยง', 'small_pet'],
            15 => ['อาหารปลาสวยงาม', 'เม็ดลอยน้ำ', 'อาหารปลาสวยงามชนิดเม็ดลอยน้ำ เร่งสี',
                null, '100 ก.', 79, null, 45, null, 'อาหาร', 'fish'],
            16 => ['ตู้ปลาพร้อมไฟ LED', 'ตู้ขนาด 12 นิ้ว', 'ตู้ปลาขนาด 12 นิ้ว พร้อมไฟ LED และปั๊มกรอง',
                null, '12 นิ้ว', 990, 1190, 5, null, 'อุปกรณ์เลี้ยง', 'fish'],
        ];
        // FOOD subclass: flavor / ACCESSORY subclass: material (EER specialization)
        $flavors = [1 => 'ไก่และข้าว', 5 => 'นมและไก่', 6 => 'มินต์', 7 => 'ปลาทูน่า', 8 => 'แซลมอน',
                    13 => 'ธัญพืชรวม', 15 => 'กุ้งและสาหร่าย'];
        $materials = [2 => 'ไหมพรม/ขนนก', 3 => 'ผ้าฝ้าย', 4 => 'ไนลอน', 9 => 'เต้าหู้', 10 => 'ไม้/เชือกป่าน',
                      11 => 'พลาสติก/สแตนเลส', 12 => 'ขวดพลาสติก', 14 => 'ลวดเหล็ก/พลาสติก', 16 => 'กระจก'];

        $p = [];
        foreach ($rows as $no => [$name, $short, $desc, $brand, $size, $price, $orig, $stock, $badge, $category, $pet]) {
            $p[$no] = Product::create([
                'name' => $name,
                'short_description' => $short,
                'description' => $desc,
                'brand' => $brand,
                'size' => $size,
                'price' => $price,
                'original_price' => $orig,
                'stock' => $stock,
                'badge' => $badge,
                'pet_type' => $pet,
                'product_type' => in_array($category, ['อาหาร', 'ขนม']) ? 'food' : 'accessory',
                'flavor' => $flavors[$no] ?? null,
                'material' => $materials[$no] ?? null,
                'category_id' => $cats[$category]->id,
            ]);
        }

        // ---------- reviews ----------
        $this->review($somchai, $p[1], 5, 'น้องกินง่าย เม็ดพอดี ส่งเร็ว แพ็กของดีมากค่ะ', '2026-10-12 18:00:00');
        $this->review($malee, $p[1], 5, 'หมาที่บ้านกินหมดชามทุกมื้อ', '2026-10-13 09:30:00');
        $this->review($malee, $p[2], 5, 'แมวที่บ้านเล่นทั้งวัน', '2026-10-13 20:10:00');
        $this->review($malee, $p[9], 4, 'จับก้อนดี กลิ่นไม่ค่อยมี แต่ฝุ่นนิดหน่อย', '2026-10-14 09:45:00');
        $this->review($somchai, $p[4], 4, 'ทนดี ปรับความยาวง่าย', '2026-10-15 12:00:00');

        // ---------- wish lists ----------
        $somchai->wishlist()->attach([$p[1]->id, $p[2]->id, $p[3]->id]);
        $malee->wishlist()->attach($p[10]->id);

        // ---------- past orders ----------
        $this->order($somchai, 'transfer', 'shipping', '2026-10-20 15:20:00', [[$p[1], 1], [$p[2], 2]]);
        $this->order($malee, 'cod', 'delivered', '2026-10-11 19:05:00', [[$p[7], 1], [$p[8], 1]]);

        $this->command->info('Shop sample data created.');
    }

    private function makeUser(string $first, string $last, string $email, string $password,
                              string $phone, string $address, string $province, string $postal): User
    {
        $user = new User();
        $user->forceFill([
            'name' => $first,
            'last_name' => $last,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'phone' => $phone,
            'address' => $address,
            'province' => $province,
            'postal_code' => $postal,
        ])->save();

        return $user;
    }

    private function review(User $user, Product $product, int $rating, string $comment, string $date): void
    {
        Review::query()->forceCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }

    private function order(User $user, string $payment, string $status, string $date, array $items): void
    {
        $subtotal = 0;
        foreach ($items as [$product, $qty]) {
            $subtotal += $product->price * $qty;
        }

        $order = Order::query()->forceCreate([
            'user_id' => $user->id,
            'recipient_name' => $user->fullName(),
            'recipient_phone' => $user->phone,
            'shipping_address' => $user->address,
            'province' => $user->province,
            'postal_code' => $user->postal_code,
            'payment_method' => $payment,
            'subtotal' => $subtotal,
            'shipping_fee' => Order::SHIPPING_FEE,
            'total' => $subtotal + Order::SHIPPING_FEE,
            'status' => $status,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        foreach ($items as [$product, $qty]) {
            $order->products()->attach($product->id, [
                'quantity' => $qty,
                'unit_price' => $product->price,
            ]);
        }
    }
}
