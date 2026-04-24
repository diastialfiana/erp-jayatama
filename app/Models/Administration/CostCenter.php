<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk Cost Center yang digunakan oleh modul Finance (Cash In, Cash Receipt, dll.)
 * Maps ke tabel cost_centers yang dikelola oleh App\Models\CostCenter.
 */
class CostCenter extends Model
{
    protected $table = 'cost_centers';

    protected $fillable = [
        'code',
        'description',
        'audit',
    ];

    /**
     * Alias 'name' untuk kompatibilitas dengan select2 / dropdown di controller.
     */
    public function getNameAttribute(): string
    {
        return $this->description ?? $this->code;
    }
}
