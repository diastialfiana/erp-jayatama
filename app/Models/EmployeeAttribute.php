<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttribute extends Model
{
    protected $fillable = ['employee_id', 'date', 'user_no', 'attribute_name'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
