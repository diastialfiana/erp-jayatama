<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('account_transactions', function (Blueprint $table) {
            $table->string('user_no')->nullable()->after('date');
            $table->string('note')->nullable()->after('user_no');
            $table->decimal('balance', 18, 2)->default(0)->after('credit');
            $table->string('currency', 10)->nullable()->after('ref');
            $table->decimal('rate', 18, 6)->default(1)->after('currency');
            $table->string('link')->nullable()->after('rate');

            $table->index(['account_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::table('account_transactions', function (Blueprint $table) {
            $table->dropIndex(['account_id', 'date']);
            $table->dropColumn(['user_no', 'note', 'balance', 'currency', 'rate', 'link']);
        });
    }
};

