<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'route'];

    public function permissions()
    {
        return $this->hasMany(MenuUserPermission::class);
    }
}

