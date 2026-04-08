<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class CashTransferDetail extends Model
{
    protected $fillable = [
        'cash_transfer_id', 'to_bank_id', 'cost_id', 'dept_id', 'currency', 'rate', 'amount', 'description'
    ];

    public function cashTransfer()
    {
        return $this->belongsTo(CashTransfer::class);
    }

    public function toBank()
    {
        return $this->belongsTo(\App\Models\Finance\BankAccount::class, 'to_bank_id');
    }
}
