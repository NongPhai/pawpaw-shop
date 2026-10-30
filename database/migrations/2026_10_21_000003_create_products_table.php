<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_description')->nullable();      // บรรทัดสั้นใต้ชื่อ เช่น "โภชนาการที่สมดุล..."
            $table->text('description')->nullable();               // รายละเอียดสินค้า
            $table->string('brand', 100)->nullable();              // Happy Tails, Meow House
            $table->string('size', 50)->nullable();                // ขนาด 2 กก.
            $table->decimal('price', 10, 2);                       // ราคาขาย
            $table->decimal('original_price', 10, 2)->nullable();  // ราคาเดิม (ขีดฆ่า)
            $table->unsignedInteger('stock')->default(0);          // มีสินค้าพร้อมส่ง / หมด
            $table->string('badge', 50)->nullable();               // ขายดี, ของใหม่, ขายดีอันดับ 1
            $table->string('image')->nullable();
            $table->enum('pet_type', ['dog', 'cat', 'small_pet', 'fish']);  // สุนัข, แมว, นกและสัตว์เล็ก, ปลา
            // EER: PRODUCT is specialised (disjoint, total) into FOOD and ACCESSORY.
            // Mapped to one table: product_type is the defining attribute,
            // flavor belongs to FOOD only and material to ACCESSORY only.
            $table->enum('product_type', ['food', 'accessory']);
            $table->string('flavor', 100)->nullable();     // FOOD: รสชาติ
            $table->string('material', 100)->nullable();   // ACCESSORY: วัสดุ
            $table->foreignId('category_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
