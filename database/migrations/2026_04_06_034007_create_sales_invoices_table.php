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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('date');
            $table->date('due_date');
            $table->unsignedBigInteger('customer_id');
            $table->string('currency');
            $table->decimal('rate', 10, 2)->default(1);
            $table->string('reference')->nullable();
            
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('pph23', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            
            $table->boolean('approved')->default(false);
            $table->text('note')->nullable();
            
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
