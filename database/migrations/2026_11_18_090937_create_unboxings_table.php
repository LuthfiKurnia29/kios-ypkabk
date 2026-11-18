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
        Schema::create('unboxings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url', 500);
            $table->string('platform', 30)->default('youtube');
            $table->date('recorded_at')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unboxings');
    }
};
