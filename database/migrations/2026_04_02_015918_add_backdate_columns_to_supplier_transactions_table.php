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
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->date('original_date')->nullable()->after('reference_no');
            $table->date('transaction_date')->nullable()->after('original_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_transactions', function (Blueprint $table) {
            $table->dropColumn(['original_date', 'transaction_date']);
        });
    }
};
