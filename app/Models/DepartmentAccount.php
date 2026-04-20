<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentAccount extends Model
{
    protected $table = 'department_accounts';

    protected $fillable = [
        'code',
        'description',
        'audit',
    ];

    public function transactions()
    {
        return $this->hasMany(\App\Models\AccountTransaction::class, 'department_id');
    }
}
