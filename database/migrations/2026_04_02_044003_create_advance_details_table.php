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
        Schema::create('advance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advance_id')->constrained('advances')->cascadeOnDelete();
            $table->string('account_id')->nullable();
            $table->string('dept_id')->nullable();
            $table->string('cost_id')->nullable();
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
        Schema::dropIfExists('advance_details');
    }
};
