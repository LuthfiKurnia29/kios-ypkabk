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
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('purchase_id')->nullable()->after('quote')->constrained('purchases')->nullOnDelete();
            $table->foreignId('donation_id')->nullable()->after('purchase_id')->constrained('donations')->nullOnDelete();
            $table->enum('source', ['buyer', 'donor'])->nullable()->after('donation_id');

            $table->unique('purchase_id');
            $table->unique('donation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['purchase_id']);
            $table->dropUnique(['donation_id']);
            $table->dropConstrainedForeignId('purchase_id');
            $table->dropConstrainedForeignId('donation_id');
            $table->dropColumn('source');
        });
    }
};
