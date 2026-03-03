<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use SoftDeletes;

    protected $table = 'jabatan';

    protected $fillable = ['kode_jabatan', 'nama_jabatan', 'level', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
