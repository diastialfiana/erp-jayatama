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
        Schema::table('product_assets', function (Blueprint $table) {
            $table->integer('mar_val')->default(0);
            $table->integer('apr_val')->default(0);
            $table->integer('may_val')->default(0);
            $table->integer('jun_val')->default(0);
            $table->integer('jul_val')->default(0);
            $table->integer('aug_val')->default(0);
            $table->integer('sep_val')->default(0);
            $table->integer('oct_val')->default(0);
            $table->integer('nov_val')->default(0);
            $table->integer('dec_val')->default(0);
            
            // Statistics
            $table->date('last_received')->nullable();
            $table->date('last_issued')->nullable();
            $table->date('last_sold')->nullable();
            $table->integer('ytd_received')->default(0);
            $table->integer('ytd_issued')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_assets', function (Blueprint $table) {
            $table->dropColumn([
                'mar_val', 'apr_val', 'may_val', 'jun_val', 'jul_val', 'aug_val',
                'sep_val', 'oct_val', 'nov_val', 'dec_val',
                'last_received', 'last_issued', 'last_sold', 'ytd_received', 'ytd_issued'
            ]);
        });
    }
};
