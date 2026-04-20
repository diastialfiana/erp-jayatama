<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * BankTransaction mencatat SETIAP pergerakan kas di bank account,
     * dari source manapun (cash_in, cash_out, cash_receipt, cash_transfer).
     */
    public function up(): void
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bank_account_id')
                  ->constrained('bank_accounts')
                  ->cascadeOnDelete();

            // Source reference: module mana yang membuat transaksi ini
            $table->string('source_type')
                  ->comment('cash_in|cash_out|cash_receipt|cash_transfer_in|cash_transfer_out|advance|manual');
            $table->unsignedBigInteger('source_id')->nullable()
                  ->comment('ID di table source (cash_ins.id, cash_receipts.id, dsb)');

            $table->date('date');
            $table->string('reference')->nullable();
            $table->string('description')->nullable();

            $table->decimal('debit',  15, 2)->default(0)
                  ->comment('Kas keluar dari bank (payment, transfer out, dsb)');
            $table->decimal('credit', 15, 2)->default(0)
                  ->comment('Kas masuk ke bank (receipt, transfer in, dsb)');
            $table->decimal('running_balance', 15, 2)->default(0)
                  ->comment('Saldo berjalan setelah transaksi ini');

            $table->string('currency')->default('IDR');
            $table->decimal('rate', 15, 2)->default(1);

            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();

            // Index untuk performa query aktivitas & backdate
            $table->index(['bank_account_id', 'date']);
            $table->index(['bank_account_id', 'source_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
