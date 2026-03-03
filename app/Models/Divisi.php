<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Divisi extends Model
{
    use SoftDeletes;

    protected $table = 'divisi';

    protected $fillable = ['kode_divisi', 'nama_divisi', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
