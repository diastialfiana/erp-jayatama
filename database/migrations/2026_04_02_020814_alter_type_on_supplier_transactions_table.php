<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE supplier_transactions MODIFY COLUMN type VARCHAR(50)");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            // Can't reliably go back to enum generically without parsing existing records and failing if new strings exist.
            // \Illuminate\Support\Facades\DB::statement("ALTER TABLE supplier_transactions... ");
        });
    }
};
