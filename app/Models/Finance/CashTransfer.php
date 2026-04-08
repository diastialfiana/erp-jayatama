<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class CashTransfer extends Model
{
    protected $fillable = [
        'date', 'reference', 'currency', 'rate', 'from_bank_id', 'total_amount', 'note', 'created_by'
    ];

    public function details()
    {
        return $this->hasMany(CashTransferDetail::class);
    }

    public function fromBank()
    {
        return $this->belongsTo(\App\Models\Finance\BankAccount::class, 'from_bank_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
