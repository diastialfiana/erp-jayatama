<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class CashOut extends Model
{
    protected $fillable = [
        'date', 'reference', 'currency', 'rate', 'bank_account_id', 
        'employee_id', 'ca_reference', 'is_down_payment', 'note', 'created_by'
    ];

    protected $casts = [
        'is_down_payment' => 'boolean',
    ];

    public function details()
    {
        return $this->hasMany(CashOutDetail::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(\App\Models\Finance\BankAccount::class, 'bank_account_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function employee()
    {
        // Default to User model if Employee model does not exist
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }
}
