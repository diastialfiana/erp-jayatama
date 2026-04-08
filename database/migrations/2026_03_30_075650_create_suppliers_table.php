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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('category')->nullable();
            $table->integer('due_days')->default(0);
            $table->decimal('credit_limit', 15, 2)->default(0);
            
            // Bank Info
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('account_name')->nullable();
            
            // Account Relations
            $table->unsignedBigInteger('payable_account_id')->nullable();
            $table->unsignedBigInteger('prepaid_account_id')->nullable();
            $table->unsignedBigInteger('pph23_account_id')->nullable();
            $table->unsignedBigInteger('tax_account_id')->nullable();
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('account_dept_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
