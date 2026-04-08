<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class CashOutDetail extends Model
{
    protected $fillable = [
        'cash_out_id', 'account_id', 'dept_id', 'cost_id', 'amount', 'description'
    ];

    public function cashOut()
    {
        return $this->belongsTo(CashOut::class);
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\Finance\ChartOfAccount::class, 'account_id');
    }
}
