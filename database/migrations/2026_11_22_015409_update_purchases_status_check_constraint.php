<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE purchases DROP CONSTRAINT IF EXISTS purchases_status_check;');
            DB::statement("ALTER TABLE purchases ADD CONSTRAINT purchases_status_check CHECK (status IN ('waiting_approval', 'pending', 'paid', 'shipped', 'completed', 'cancelled'));");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE purchases DROP CONSTRAINT IF EXISTS purchases_status_check;');
            DB::statement("ALTER TABLE purchases ADD CONSTRAINT purchases_status_check CHECK (status IN ('pending', 'paid', 'cancelled'));");
        }
    }
};
