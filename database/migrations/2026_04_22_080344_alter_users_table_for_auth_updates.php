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
        // Using DB::statement for compatibility with MariaDB 10.4 (which doesn't support RENAME COLUMN)
        DB::statement("ALTER TABLE users CHANGE nip username VARCHAR(255)");
        DB::statement("ALTER TABLE users CHANGE nama_lengkap name VARCHAR(255)");

        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('is_active');
            $table->enum('role', ['super_admin', 'user'])->default('user')->after('password');
            $table->timestamp('last_login')->nullable()->after('remember_token');
        });

        DB::statement("UPDATE users SET status = IF(is_active = 1, 'active', 'inactive')");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        DB::statement("ALTER TABLE password_reset_tokens CHANGE nip username VARCHAR(255)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE password_reset_tokens CHANGE username nip VARCHAR(255)");

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('status');
        });

        DB::statement("UPDATE users SET is_active = IF(status = 'active', 1, 0)");

        // Using DB::statement for compatibility with MariaDB 10.4
        DB::statement("ALTER TABLE users CHANGE username nip VARCHAR(255)");
        DB::statement("ALTER TABLE users CHANGE name nama_lengkap VARCHAR(255)");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('role');
            $table->dropColumn('last_login');
        });
    }
};
