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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            $table->decimal('price', 12, 2)->default(0)->after('name');
            $table->text('description')->nullable()->after('price');
            $table->string('image')->nullable()->after('description');
            $table->enum('source', ['karya', 'preloved'])->default('preloved')->after('image');
            $table->enum('condition', ['baru', 'mulus', 'layak'])->default('mulus')->after('source');
            $table->string('artisan_name')->nullable()->after('condition');
            $table->boolean('is_featured')->default(false)->after('isEmpty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'category_id',
                'price',
                'description',
                'image',
                'source',
                'condition',
                'artisan_name',
                'is_featured',
            ]);
        });
    }
};
