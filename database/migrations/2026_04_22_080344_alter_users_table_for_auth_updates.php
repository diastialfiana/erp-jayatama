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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('nip', 'username');
            $table->renameColumn('nama_lengkap', 'name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('is_active');
            $table->enum('role', ['super_admin', 'user'])->default('user')->after('password');
            $table->timestamp('last_login')->nullable()->after('remember_token');
        });

        DB::statement("UPDATE users SET status = IF(is_active = 1, 'active', 'inactive')");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->renameColumn('nip', 'username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->renameColumn('username', 'nip');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('status');
        });

        DB::statement("UPDATE users SET is_active = IF(status = 'active', 1, 0)");

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('username', 'nip');
            $table->renameColumn('name', 'nama_lengkap');
            $table->dropColumn('status');
            $table->dropColumn('role');
            $table->dropColumn('last_login');
        });
    }
};
