<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bank_accounts';

    protected $fillable = [
        'branch_id',
        'code',
        'currency',
        'bank_name',
        'description',
        'category',
        'bank_account',
        'ar_account',
        'cost_center',
        'department',
        'credit_limit',
        'is_default',
    ];

    protected $casts = [
        'is_default'   => 'boolean',
        'credit_limit' => 'decimal:2',
    ];

    public function getBalanceAttribute()
    {
        // credit = cash in, debit = cash out → balance = SUM(credit) - SUM(debit)
        return $this->transactions()
            ->selectRaw('COALESCE(SUM(credit - debit), 0) as balance')
            ->value('balance');
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class, 'bank_account_id');
    }
}
