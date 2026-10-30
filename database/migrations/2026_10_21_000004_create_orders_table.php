<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();                                           // shown as #PP-1024 in the UI
            $table->foreignId('user_id')->constrained();
            // shipping details entered on the checkout page (copied at the time of the order)
            $table->string('recipient_name');
            $table->string('recipient_phone', 20);
            $table->text('shipping_address');
            $table->string('province', 100);
            $table->string('postal_code', 10);
            $table->enum('payment_method', ['transfer', 'cod']);   // โอนเงิน / เก็บเงินปลายทาง
            $table->decimal('subtotal', 10, 2);                     // รวมสินค้า
            $table->decimal('shipping_fee', 10, 2)->default(0);     // ค่าจัดส่ง
            $table->decimal('total', 10, 2);                        // ยอดชำระทั้งหมด
            $table->enum('status', ['pending', 'paid', 'shipping', 'delivered'])->default('pending');
            $table->timestamps();                                   // created_at = order date
        });

        // M:N - products in each order
        Schema::create('order_items', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);                   // price at the time of the order
            $table->primary(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
