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
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('business_unit')->nullable();
            $table->decimal('paid', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('po_customer')->nullable();
            $table->string('quotation')->nullable();
            $table->string('tax_no')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('audit')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id', 'business_unit', 'paid', 'discount', 'balance', 
                'po_customer', 'quotation', 'tax_no', 'receipt_no', 'audit'
            ]);
        });
    }
};
