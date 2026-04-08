<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('code')->unique();
            $table->string('currency')->default('IDR');
            $table->string('bank_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // BANK LOCAL, BANK FOREIGN, CASH
            $table->string('bank_account')->nullable(); // Dropdown bank name
            $table->string('ar_account')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('department')->nullable();
            $table->decimal('credit_limit', 18, 2)->default(0);
            $table->decimal('balance', 18, 2)->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
