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
        Schema::create('cash_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_transfer_id')->constrained('cash_transfers')->cascadeOnDelete();
            $table->unsignedBigInteger('to_bank_id');
            $table->unsignedBigInteger('cost_id')->nullable();
            $table->unsignedBigInteger('dept_id')->nullable();
            $table->string('currency')->default('IDR');
            $table->decimal('rate', 15, 6)->default(1);
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transfer_details');
    }
};
