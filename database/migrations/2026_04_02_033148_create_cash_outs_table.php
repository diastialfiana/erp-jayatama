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
        Schema::create('cash_outs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference')->nullable();
            $table->string('currency')->default('IDR');
            $table->decimal('rate', 15, 6)->default(1);
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('ca_reference')->nullable();
            $table->boolean('is_down_payment')->default(false);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_outs');
    }
};
