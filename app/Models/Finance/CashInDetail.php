<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class CashInDetail extends Model
{
    protected $fillable = ['cash_in_id', 'account_id', 'dept_id', 'cost_id', 'amount', 'description'];

    public function cashIn()
    {
        return $this->belongsTo(CashIn::class);
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }
}
