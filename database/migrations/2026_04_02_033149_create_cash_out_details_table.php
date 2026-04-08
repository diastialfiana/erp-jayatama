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
        Schema::create('cash_out_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_out_id')->constrained('cash_outs')->cascadeOnDelete();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('dept_id')->nullable();
            $table->unsignedBigInteger('cost_id')->nullable();
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
        Schema::dropIfExists('cash_out_details');
    }
};
