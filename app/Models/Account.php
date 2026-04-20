<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'branch_id',
        'currency_id',
        'parent_id',
        'cost_center_id',
        'balance',
        'is_control',
        'is_active'
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AccountTransaction::class, 'account_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(AccountBudget::class, 'account_id');
    }
}
