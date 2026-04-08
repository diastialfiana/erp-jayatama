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
        Schema::create('cash_in_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_in_id')->index();
            $table->unsignedBigInteger('account_id')->nullable()->index();
            $table->unsignedBigInteger('dept_id')->nullable();
            $table->unsignedBigInteger('cost_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
            
            $table->foreign('cash_in_id')->references('id')->on('cash_ins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_in_details');
    }
};
