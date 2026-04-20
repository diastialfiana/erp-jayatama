<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountBudget extends Model
{
    protected $fillable = [
        'account_id',
        'year',
        'month',
        'amount',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
