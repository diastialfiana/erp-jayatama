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
        'balance',
        'is_default',
    ];

    protected $casts = [
        'is_default'   => 'boolean',
        'credit_limit' => 'decimal:2',
        'balance'      => 'decimal:2',
    ];
}
