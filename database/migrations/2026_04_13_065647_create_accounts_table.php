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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('name');

            $table->enum('type', [
                'asset',
                'liability',
                'equity',
                'revenue',
                'expense'
            ]);

            $table->foreignId('branch_id')->nullable();
            $table->foreignId('currency_id')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('accounts');

            $table->foreignId('cost_center_id')->nullable()->constrained();

            $table->boolean('is_control')->default(false);
            $table->boolean('is_active')->default(true);

            $table->decimal('balance', 18, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
