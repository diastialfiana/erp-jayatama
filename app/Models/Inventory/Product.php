<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'default_price'];

    public function getSelectNameAttribute()
    {
        return $this->code . ' - ' . $this->name;
    }
}
