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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('module')->nullable()->after('id');
            $table->unsignedBigInteger('reference_id')->nullable()->after('module');
            $table->string('description')->nullable()->after('action');
            $table->decimal('amount', 15, 2)->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['module', 'reference_id', 'description', 'amount']);
        });
    }
};
