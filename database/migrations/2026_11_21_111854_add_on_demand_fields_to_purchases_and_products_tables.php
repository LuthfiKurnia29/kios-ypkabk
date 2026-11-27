<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (! Schema::hasColumn('purchases', 'order_type')) {
                $table->string('order_type', 30)->default('regular')->after('order_code');
            }
            if (! Schema::hasColumn('purchases', 'design_file_path')) {
                $table->string('design_file_path')->nullable()->after('payment_proof');
            }
            if (! Schema::hasColumn('purchases', 'design_notes')) {
                $table->text('design_notes')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('purchases', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('design_notes');
            }

            // Ensure status column can accommodate all statuses including waiting_approval
            $table->string('status', 30)->default('pending')->change();
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE purchases DROP CONSTRAINT IF EXISTS purchases_status_check;');
            DB::statement("ALTER TABLE purchases ADD CONSTRAINT purchases_status_check CHECK (status IN ('waiting_approval', 'pending', 'paid', 'shipped', 'completed', 'cancelled'));");
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE purchases DROP CONSTRAINT IF EXISTS purchases_status_check;');
            DB::statement("ALTER TABLE purchases ADD CONSTRAINT purchases_status_check CHECK (status IN ('waiting_approval', 'pending', 'paid', 'shipped', 'completed', 'cancelled'));");
        }

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'is_on_demand')) {
                $table->boolean('is_on_demand')->default(false)->after('is_featured');
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
                Schema::hasColumn('purchases', 'order_type') ? 'order_type' : null,
                Schema::hasColumn('purchases', 'design_file_path') ? 'design_file_path' : null,
                Schema::hasColumn('purchases', 'design_notes') ? 'design_notes' : null,
                Schema::hasColumn('purchases', 'admin_notes') ? 'admin_notes' : null,
            ]);

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_on_demand')) {
                $table->dropColumn('is_on_demand');
            }
        });
    }
};
