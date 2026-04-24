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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nip')->unique();
            $table->string('full_name');
            $table->string('nick_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('position')->nullable();
            $table->string('work_at')->nullable();
            $table->string('location')->nullable();
            $table->date('join_date')->nullable();
            $table->enum('clothes_size', ['S', 'M', 'L', 'XL', 'XXL'])->nullable();
            $table->string('pants_size')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('id_card_print')->default(false);
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
