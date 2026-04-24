<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk Department yang digunakan oleh modul Finance (Cash In, Cash Receipt, dll.)
 * Maps ke tabel departments.
 */
class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
    ];
}
