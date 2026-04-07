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
        Schema::create('product_asset_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('product_asset_code');
            $table->string('warehouse_name');
            $table->integer('stock')->default(0);
            $table->integer('on_transit')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_asset_warehouses');
    }
};
