<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->string('account_code')->nullable()->after('ar_account');   // GL Account e.g. 112001IDR
            $table->string('audit')->nullable()->after('is_default');          // Audit status e.g. PASS, name
        });
    }

    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn(['account_code', 'audit']);
        });
    }
};
