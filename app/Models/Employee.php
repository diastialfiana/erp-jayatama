<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'code', 'nip', 'full_name', 'nick_name', 'mobile', 'position', 'work_at', 'location', 
        'join_date', 'clothes_size', 'pants_size', 'email', 'is_active', 'id_card_print', 'photo'
    ];

    public function files()
    {
        return $this->hasMany(EmployeeFile::class);
    }

    public function attributes()
    {
        return $this->hasMany(EmployeeAttribute::class);
    }

    use HasFactory;
}
