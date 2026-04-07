<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFile extends Model
{
    protected $fillable = ['employee_id', 'description', 'is_checked'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
