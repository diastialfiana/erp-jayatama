<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advance extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(AdvanceDetail::class);
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\User::class, 'employee_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(\App\Models\Finance\BankAccount::class, 'bank_account_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
