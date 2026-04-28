<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class BranchLocation extends Model
{
    protected $fillable = [
        'code',
        'name',
        'address',
        'inventory_account_id',
        'cogs_account_id',
        'cost_center_id',
        'department_id',
    ];

    public function inventoryAccount()
    {
        return $this->belongsTo(\App\Models\Account::class, 'inventory_account_id');
    }

    public function cogsAccount()
    {
        return $this->belongsTo(\App\Models\Account::class, 'cogs_account_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(\App\Models\Administration\CostCenter::class, 'cost_center_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Administration\Department::class, 'department_id');
    }
}
