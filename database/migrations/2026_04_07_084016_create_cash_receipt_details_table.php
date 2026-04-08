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
        Schema::create('cash_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('cash_receipts')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('sales_invoices')->nullOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('prepaid', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_receipt_details');
    }
};
