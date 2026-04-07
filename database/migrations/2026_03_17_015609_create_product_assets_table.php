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
        Schema::create('product_assets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('product_name');
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('price_gr', 15, 2)->nullable();
            $table->decimal('price_ec', 15, 2)->nullable();
            $table->integer('min_stock')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('on_order')->default(0);
            $table->boolean('discontinue')->default(false);
            
            // Stats
            $table->integer('jan_val')->default(0);
            $table->integer('feb_val')->default(0);
            
            // Warehouse stock (simplified for single warehouse view, real app might use a separate table)
            $table->integer('wh_stock')->default(0);
            $table->string('warehouse_name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_assets');
    }
};
