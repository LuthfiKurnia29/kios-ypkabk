<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('order_code')->nullable()->unique()->after('id');
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('customer_address')->nullable()->after('customer_phone');
            $table->decimal('total_price', 12, 2)->default(0)->after('customer_address');
            $table->string('payment_method')->nullable()->after('total_price');
            $table->string('payment_proof')->nullable()->after('payment_method');
            $table->text('notes')->nullable()->after('payment_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'order_code',
                'customer_name',
                'customer_phone',
                'customer_address',
                'total_price',
                'payment_method',
                'payment_proof',
                'notes',
            ]);
        });
    }
};
