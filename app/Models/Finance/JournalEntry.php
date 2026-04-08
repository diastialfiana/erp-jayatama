<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_id',
        'account_id',
        'department_id',
        'cost_center_id',
        'debit',
        'credit',
        'description',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function receipt()
    {
        return $this->belongsTo(CashReceipt::class, 'receipt_id');
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Administration\Department::class, 'department_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(\App\Models\Administration\CostCenter::class, 'cost_center_id');
    }
}
