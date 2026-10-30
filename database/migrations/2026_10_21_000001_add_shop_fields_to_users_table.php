<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // `name` from the starter kit holds the first name (ชื่อจริง)
            $table->string('last_name')->nullable()->after('name');                // นามสกุล
            $table->string('phone', 20)->nullable()->unique()->after('password');  // used for login too
            $table->text('address')->nullable()->after('phone');                   // บ้านเลขที่ / ถนน / แขวง / เขต
            $table->string('province', 100)->nullable()->after('address');
            $table->string('postal_code', 10)->nullable()->after('province');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']);
            $table->dropColumn(['last_name', 'phone', 'address', 'province', 'postal_code']);
        });
    }
};
