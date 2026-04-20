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
        Schema::table('bank_transactions', function (Blueprint $table) {
            // Drop old index first to avoid error before dropping columns
            $table->dropIndex(['bank_account_id', 'source_type']);
            
            $table->dropColumn(['source_type', 'source_id', 'running_balance']);

            $table->string('type')->after('bank_account_id')->comment('cash_in|cash_out|cash_receipt|advance|manual|transfer');
            $table->unsignedBigInteger('reference_id')->nullable()->after('type');
            $table->string('reference_type')->nullable()->after('reference_id');
            
            $table->unsignedBigInteger('account_id')->nullable()->after('reference_type');
            $table->unsignedBigInteger('department_id')->nullable()->after('account_id');
            $table->unsignedBigInteger('cost_center_id')->nullable()->after('department_id');
            
            $table->decimal('amount', 15, 2)->default(0)->after('cost_center_id');

            $table->index(['bank_account_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_transactions', function (Blueprint $table) {
            $table->dropIndex(['bank_account_id', 'type']);
            
            $table->dropColumn([
                'type', 'reference_id', 'reference_type', 
                'account_id', 'department_id', 'cost_center_id', 'amount'
            ]);

            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->decimal('running_balance', 15, 2)->default(0);

            $table->index(['bank_account_id', 'source_type']);
        });
    }
};
