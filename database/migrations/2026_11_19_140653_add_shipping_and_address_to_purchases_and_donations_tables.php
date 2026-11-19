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
            if (! Schema::hasColumn('purchases', 'city')) {
                $table->string('city')->nullable()->after('customer_phone');
            }
            if (! Schema::hasColumn('purchases', 'district')) {
                $table->string('district')->nullable()->after('city');
            }
            if (! Schema::hasColumn('purchases', 'village')) {
                $table->string('village')->nullable()->after('district');
            }
            if (! Schema::hasColumn('purchases', 'street_address')) {
                $table->string('street_address')->nullable()->after('village');
            }
            if (! Schema::hasColumn('purchases', 'address')) {
                $table->text('address')->nullable()->after('street_address');
            }
            if (! Schema::hasColumn('purchases', 'payment_method')) {
                $table->string('payment_method')->default('qris')->after('total_price');
            }
            if (! Schema::hasColumn('purchases', 'courier_name')) {
                $table->string('courier_name')->nullable()->after('payment_proof');
            }
            if (! Schema::hasColumn('purchases', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('courier_name');
            }
            if (! Schema::hasColumn('purchases', 'shipping_receipt_path')) {
                $table->string('shipping_receipt_path')->nullable()->after('tracking_number');
            }
        });

        Schema::table('donations', function (Blueprint $table) {
            if (! Schema::hasColumn('donations', 'item_photo_path')) {
                $table->string('item_photo_path')->nullable()->after('item_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $columnsToDrop = array_filter([
                'city',
                'district',
                'village',
                'street_address',
                'address',
                'courier_name',
                'tracking_number',
                'shipping_receipt_path',
            ], fn (string $col) => Schema::hasColumn('purchases', $col));

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::table('donations', function (Blueprint $table) {
            if (Schema::hasColumn('donations', 'item_photo_path')) {
                $table->dropColumn('item_photo_path');
            }
        });
    }
};
