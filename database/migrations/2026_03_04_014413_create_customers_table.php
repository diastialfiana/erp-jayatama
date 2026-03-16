<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('counter_name');
            $table->string('currency')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('region')->nullable();
            $table->string('initial_name')->nullable();
            $table->string('invoice_layout')->nullable(); // detail, project
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('account_dept_id')->nullable();
            $table->unsignedBigInteger('default_bank_account_id')->nullable();
            $table->boolean('is_corporate_group')->default(false);
            $table->unsignedBigInteger('group_id')->nullable(); // For "As Groups"

            // Account Relations
            $table->unsignedBigInteger('receivable_account_id')->nullable();
            $table->unsignedBigInteger('prepaid_account_id')->nullable();
            $table->unsignedBigInteger('pph23_account_id')->nullable();
            $table->unsignedBigInteger('tax_account_id')->nullable();
            $table->unsignedBigInteger('sales_account_id')->nullable();
            $table->unsignedBigInteger('sales_return_account_id')->nullable();

            // Financial Summary
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('down_payment', 15, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
