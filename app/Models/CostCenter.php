<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    protected $table = 'cost_centers';

    protected $fillable = [
        'code',
        'description',
        'audit',
    ];

    public function transactions()
    {
        return $this->hasMany(\App\Models\AccountTransaction::class, 'cost_center_id');
    }
}
