<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class CashIn extends Model
{
    protected $fillable = ['date', 'reference', 'currency', 'rate', 'bank_account_id', 'note', 'created_by'];

    public function details()
    {
        return $this->hasMany(CashInDetail::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
