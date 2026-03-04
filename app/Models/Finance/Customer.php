<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        'code',
        'counter_name',
        'currency',
        'address',
        'phone',
        'fax',
        'mobile_phone',
        'region',
        'initial_name',
        'invoice_layout',
        'cost_center_id',
        'account_dept_id',
        'default_bank_account_id',
        'is_corporate_group',
        'group_id',
        'receivable_account_id',
        'prepaid_account_id',
        'pph23_account_id',
        'tax_account_id',
        'sales_account_id',
        'sales_return_account_id',
        'balance',
        'down_payment',
    ];

    protected $casts = [
        'is_corporate_group' => 'boolean',
        'balance' => 'decimal:2',
        'down_payment' => 'decimal:2',
    ];

    // Additional relationships with Chart Of Account could be defined here
}
